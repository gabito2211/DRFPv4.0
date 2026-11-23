<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Bitacora;
use Carbon\Carbon;

class RespaldoSistemaController extends Controller
{
    /**
     * Catálogos globales: solo la sede principal los exporta/importa.
     */
    protected $tablasGlobales = array('sedes', 'autores', 'bitacoras');

    /**
     * Tablas de datos operativos (todas tienen columna sede_id,
     * excepto personas que es un catálogo compartido de visitantes).
     */
    protected $tablasPorSede = array(
        'users',
        'encargados',
        'salas',
        'pasillos',
        'estanterias',
        'personas',
        'inventario',
        'carnets',
        'prestamo',
        'prestamos_internos',
        'sanciones',
        'historial_danos_libros',
        'visitas',
        'visitas_guiadas',
        'planes_vacacionales',
        'actividades',
    );

    /**
     * Vista principal del módulo.
     */
    public function index()
    {
        $user = Auth::user();
        $esPrincipal = $this->esPrincipal($user);

        if ($esPrincipal) {
            $tablasAlcance = array_merge($this->tablasGlobales, $this->tablasPorSede);
        } else {
            $tablasAlcance = $this->tablasPorSede;
        }

        return view('respaldo', array(
            'esPrincipal'   => $esPrincipal,
            'tablasAlcance' => $tablasAlcance,
            'tablasGlobales' => $this->tablasGlobales,
            'tablasPorSede' => $this->tablasPorSede,
        ));
    }

    /**
     * Genera y descarga el respaldo en formato SQL.
     * - Sede principal: todo el sistema.
     * - Otras sedes: únicamente los datos de su propia sede.
     */
    public function exportar()
    {
        $user = Auth::user();
        $esPrincipal = $this->esPrincipal($user);
        $sedeId = $user->sede_id;

        $tablas = $esPrincipal
            ? array_merge($this->tablasGlobales, $this->tablasPorSede)
            : $this->tablasPorSede;

        $sql = '';
        $sql .= "-- =====================================================\n";
        $sql .= "-- Respaldo del Sistema DRFP v4.0\n";
        $sql .= "-- Generado: " . Carbon::now()->format('d/m/Y H:i') . "\n";
        $sql .= "-- Generado por: {$user->name} <{$user->email}>\n";
        $sql .= "-- Alcance: " . ($esPrincipal ? 'TODO EL SISTEMA' : "Solo datos de la sede {$sedeId}") . "\n";
        $sql .= "-- Formato: INSERT ... ON DUPLICATE KEY UPDATE (importación segura)\n";
        $sql .= "-- =====================================================\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET UNIQUE_CHECKS=0;\n\n";

        $totalRegistros = 0;
        $resumenTablas = array();

        foreach ($tablas as $tabla) {
            list($filas, $campos) = $this->obtenerFilas($tabla, $esPrincipal, $sedeId);

            if (empty($filas)) {
                continue;
            }

            $sql .= "-- Tabla: {$tabla} (" . count($filas) . " registros)\n";
            $pk = $this->clavePrimaria($tabla, $campos);

            foreach ($filas as $fila) {
                $valores = array();
                foreach ($campos as $campo) {
                    $valores[] = $this->valorSql($fila->$campo);
                }
                $sql .= "INSERT INTO `{$tabla}` (`" . implode("`, `", $campos) . "`) VALUES (" . implode(", ", $valores) . ")\n";
                $sql .= "ON DUPLICATE KEY UPDATE " . $this->clausulaUpdate($campos, $pk) . ";\n";
                $totalRegistros++;
            }
            $sql .= "\n";
            $resumenTablas[$tabla] = count($filas);
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $sql .= "SET UNIQUE_CHECKS=1;\n";

        // Copia obligatoria en storage/backups
        $carpetaBackups = storage_path('backups');
        if (!is_dir($carpetaBackups)) {
            @mkdir($carpetaBackups, 0755, true);
        }
        $fechaArchivo = Carbon::now()->format('Ymd_His');
        $identificador = $esPrincipal ? 'sistema' : ('sede_' . $sedeId);
        $nombreArchivo = 'respaldo_' . $identificador . '_' . $fechaArchivo . '.sql';
        @file_put_contents($carpetaBackups . DIRECTORY_SEPARATOR . $nombreArchivo, $sql);

        Bitacora::registrar(
            'export',
            'sistema',
            "Se exportó el respaldo del sistema ({$totalRegistros} registros en " . count($resumenTablas) . " tablas, alcance: " . ($esPrincipal ? 'todo el sistema' : "sede {$sedeId}") . ").",
            null,
            array('archivo' => $nombreArchivo, 'total' => $totalRegistros, 'tablas' => $resumenTablas),
            $user
        );

        return response($sql, 200, array(
            'Content-Type' => 'application/sql; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ));
    }

    /**
     * Importa un archivo SQL generado por este mismo módulo.
     * - Sede principal: puede importar todo el sistema.
     * - Otras sedes: solo aceptan datos de su propia sede
     *   (cada INSERT debe incluir sede_id con su valor correcto).
     */
    public function importar(Request $request)
    {
        $this->validate($request, array(
            'archivo_sql' => 'required|file|max:51200',
        ));

        $user = Auth::user();
        $esPrincipal = $this->esPrincipal($user);
        $sedeId = $user->sede_id;

        $archivo = $request->file('archivo_sql');
        $extension = strtolower($archivo->getClientOriginalExtension());

        if ($extension !== 'sql' && $extension !== 'txt') {
            return redirect()->back()->with('error', 'Formato no permitido. Solo se aceptan archivos .sql o .txt generados por este módulo.');
        }

        $contenido = @file_get_contents($archivo->getRealPath());
        if ($contenido === false || trim($contenido) === '') {
            return redirect()->back()->with('error', 'El archivo está vacío o no se pudo leer.');
        }

        // Guardar copia obligatoria del archivo recibido
        $carpetaBackups = storage_path('backups');
        if (!is_dir($carpetaBackups)) {
            @mkdir($carpetaBackups, 0755, true);
        }
        $fechaArchivo = Carbon::now()->format('Ymd_His');
        $identificador = $esPrincipal ? 'sistema' : ('sede_' . $sedeId);
        $nombreArchivo = 'import_' . $identificador . '_' . $fechaArchivo . '.sql';
        @file_put_contents($carpetaBackups . DIRECTORY_SEPARATOR . $nombreArchivo, $contenido);

        // ===== 1. Parsear sentencias =====
        $sentencias = $this->partirSentencias($contenido);
        if ($sentencias === null) {
            return redirect()->back()->with('error', 'El archivo parece estar incompleto o corrupto (hay datos sin cerrar). Importación cancelada.');
        }

        if (count($sentencias) === 0) {
            return redirect()->back()->with('error', 'El archivo no contiene sentencias SQL válidas.');
        }

        // ===== 2. Validar seguridad y permisos =====
        $permitidas = $esPrincipal
            ? array_merge($this->tablasGlobales, $this->tablasPorSede)
            : $this->tablasPorSede;

        $error = $this->validarSentencias($sentencias, $permitidas, $esPrincipal, $sedeId);
        if ($error !== null) {
            Bitacora::registrar('import-fallido', 'sistema', "Intento de importación rechazado por validación: {$error}", null, array('archivo' => $archivo->getClientOriginalName()), $user);
            return redirect()->back()->with('error', 'Importación rechazada: ' . $error);
        }

        // ===== 3. Ejecutar dentro de una transacción =====
        $contador = array();
        try {
            DB::beginTransaction();
            DB::unprepared('SET FOREIGN_KEY_CHECKS=0');
            DB::unprepared('SET UNIQUE_CHECKS=0');

            foreach ($sentencias as $sentencia) {
                if (preg_match('/^SET\s+(FOREIGN_KEY_CHECKS|UNIQUE_CHECKS)/i', $sentencia)) {
                    continue;
                }
                if (preg_match('/^INSERT\s+INTO\s+`?(\w+)`?/i', $sentencia, $m)) {
                    $tabla = $m[1];
                    if (!isset($contador[$tabla])) {
                        $contador[$tabla] = 0;
                    }
                    $contador[$tabla]++;
                }
                DB::unprepared($sentencia);
            }

            DB::unprepared('SET FOREIGN_KEY_CHECKS=1');
            DB::unprepared('SET UNIQUE_CHECKS=1');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1');
            DB::unprepared('SET UNIQUE_CHECKS=1');
            Log::error('Error importando respaldo del sistema: ' . $e->getMessage());
            Bitacora::registrar('import-fallido', 'sistema', "Falló la importación del respaldo: " . $e->getMessage(), null, array('archivo' => $archivo->getClientOriginalName()), $user);
            return redirect()->back()->with('error', 'Error al importar (no se aplicó ningún cambio): ' . $e->getMessage());
        }

        $total = array_sum($contador);
        Bitacora::registrar(
            'import',
            'sistema',
            "Se importó un respaldo del sistema ({$total} registros en " . count($contador) . " tablas, alcance: " . ($esPrincipal ? 'todo el sistema' : "sede {$sedeId}") . ").",
            null,
            array('archivo' => $nombreArchivo, 'total' => $total, 'tablas' => $contador),
            $user
        );

        return redirect()->route('respaldo.index')->with('success', "Importación completada: {$total} registros en " . count($contador) . " tabla(s).");
    }

    // =========================================================
    // MÉTODOS AUXILIARES
    // =========================================================

    /**
     * Determina si el usuario pertenece a la sede principal
     * (misma regla que el trait AsignaSede).
     */
    protected function esPrincipal($user)
    {
        return $user && ((int) $user->sede_id === 1 || empty($user->sede_id));
    }

    /**
     * Obtiene las filas y columnas de una tabla según el alcance.
     * Devuelve array(filas, campos).
     */
    protected function obtenerFilas($tabla, $esPrincipal, $sedeId)
    {
        $campos = array_map(function ($c) {
            return $c->Field;
        }, DB::select("SHOW COLUMNS FROM `{$tabla}`"));

        $query = DB::table($tabla);

        if ($esPrincipal) {
            // Todo el sistema
        } elseif (in_array($tabla, array('sedes', 'autores', 'bitacoras'), true)) {
            // Nunca debería pasar (no se exportan), por seguridad
            return array(array(), $campos);
        } elseif ($tabla === 'personas') {
            // Catálogo compartido: solo las personas relacionadas con esta sede
            $ids = DB::table('visitas')->where('sede_id', $sedeId)->pluck('persona_id')
                ->merge(DB::table('carnets')->where('sede_id', $sedeId)->pluck('persona_id'))
                ->merge(DB::table('prestamo')->where('sede_id', $sedeId)->pluck('lector_id'))
                ->merge(DB::table('sanciones')->where('sede_id', $sedeId)->pluck('persona_id'))
                ->merge(DB::table('historial_danos_libros')->where('sede_id', $sedeId)->pluck('persona_id'))
                ->filter()
                ->unique()
                ->all();
            if (empty($ids)) {
                return array(array(), $campos);
            }
            $query->whereIn('id', $ids);
        } else {
            $query->where('sede_id', $sedeId);
        }

        return array($query->get(), $campos);
    }

    /**
     * Detecta la clave primaria de una tabla.
     */
    protected function clavePrimaria($tabla, $campos)
    {
        $info = DB::select("SHOW COLUMNS FROM `{$tabla}`");
        foreach ($info as $c) {
            if ($c->Key === 'PRI') {
                return $c->Field;
            }
        }
        // inventario tiene PK en cota pero definida como UNIQUE...
        // SHOW COLUMNS la reporta como PRI si es primary, si no:
        return $campos[0];
    }

    /**
     * Escapa un valor para el SQL generado.
     */
    protected function valorSql($val)
    {
        if ($val === null) {
            return 'NULL';
        }
        $v = addslashes($val);
        $v = str_replace(array("\n", "\r"), array("\\n", "\\r"), $v);
        return "'" . $v . "'";
    }

    /**
     * Construye la cláusula ON DUPLICATE KEY UPDATE
     * (excluye la clave primaria).
     */
    protected function clausulaUpdate($campos, $pk)
    {
        $partes = array();
        foreach ($campos as $campo) {
            if ($campo === $pk) {
                continue;
            }
            $partes[] = "`{$campo}` = VALUES(`{$campo}`)";
        }
        return implode(", ", $partes);
    }

    /**
     * Parte el contenido del archivo en sentencias SQL.
     * Acepta comentarios (-- y #) y sentencias que terminan en ';'.
     * Devuelve null si el archivo quedó incompleto.
     */
    protected function partirSentencias($contenido)
    {
        $contenido = str_replace("\r\n", "\n", $contenido);
        $contenido = preg_replace('/\/\*.*?\*\//s', ' ', $contenido);

        $sentencias = array();
        $actual = '';

        foreach (explode("\n", $contenido) as $linea) {
            $l = trim($linea);
            if ($l === '' || $l[0] === '-' || $l[0] === '#') {
                continue;
            }
            $actual .= ($actual === '' ? '' : ' ') . $l;
            if (substr($l, -1) === ';') {
                $sentencias[] = $actual;
                $actual = '';
            }
        }

        if (trim($actual) !== '') {
            return null; // quedó texto sin cerrar
        }

        return $sentencias;
    }

    /**
     * Valida todas las sentencias antes de ejecutar nada.
     * Devuelve null si todo está bien, o el mensaje de error.
     */
    protected function validarSentencias($sentencias, $permitidas, $esPrincipal, $sedeId)
    {
        foreach ($sentencias as $sentencia) {
            // Comandos peligrosos: rechazo inmediato
            if (preg_match('/\b(DROP|TRUNCATE|ALTER|RENAME|GRANT|REVOKE)\b/i', $sentencia)) {
                return "comando no permitido en la sentencia: " . mb_substr($sentencia, 0, 60);
            }
            if (preg_match('/\bDELETE\s+FROM\b/i', $sentencia)) {
                return "no se permiten sentencias DELETE (importación por inserción/actualización).";
            }
            if (preg_match('/\bUPDATE\s+`?\w+`?\s+SET\b/i', $sentencia)) {
                return "no se permiten sentencias UPDATE manuales (el archivo debe usar el formato ON DUPLICATE KEY UPDATE).";
            }
            if (preg_match('/\b(CREATE\s+(DATABASE|TABLE|USER)|USE\s+|LOCK\s+TABLES)\b/i', $sentencia)) {
                return "comando no permitido en la sentencia: " . mb_substr($sentencia, 0, 60);
            }

            // SET de verificación
            if (preg_match('/^SET\s+(FOREIGN_KEY_CHECKS|UNIQUE_CHECKS)/i', $sentencia)) {
                continue;
            }

            // Todo lo demás debe ser INSERT INTO tabla (...)
            if (!preg_match('/^INSERT\s+INTO\s+`?(\w+)`?\s*\(([^)]*)\)\s*VALUES\s*\((.*)\)\s*ON\s+DUPLICATE/i', $sentencia, $m)) {
                return "sentencia no reconocida (el archivo debe ser generado por este módulo): " . mb_substr($sentencia, 0, 60);
            }

            $tabla = $m[1];
            if (!in_array($tabla, $permitidas, true)) {
                return "la tabla '{$tabla}' no está permitida para tu nivel de acceso.";
            }

            // Limitación de sede: cada INSERT debe pertenecer a su sede
            if (!$esPrincipal && $tabla !== 'personas') {
                $cols = array_map(function ($c) {
                    return trim(trim($c), "`'");
                }, explode(',', $m[2]));

                $indice = array_search('sede_id', $cols);
                if ($indice === false) {
                    return "el INSERT en '{$tabla}' no incluye la columna sede_id.";
                }

                $valores = $this->dividirValores($m[3]);
                if ($valores === null || !isset($valores[$indice])) {
                    return "no se pudo verificar la sede en el INSERT de '{$tabla}'.";
                }

                $valor = trim($valores[$indice]);
                if (strcasecmp($valor, 'NULL') === 0 || (int) trim($valor, "'") !== (int) $sedeId) {
                    return "el archivo contiene datos de otra sede (sede_id = {$valor}). Solo puedes importar datos de la sede {$sedeId}.";
                }
            }
        }

        return null;
    }

    /**
     * Divide una tupla de valores SQL respetando comillas.
     */
    protected function dividirValores($tupla)
    {
        $partes = array();
        $actual = '';
        $enComilla = false;
        $len = strlen($tupla);

        for ($i = 0; $i < $len; $i++) {
            $c = $tupla[$i];
            if ($c === '\\') {
                $actual .= $c;
                if ($i + 1 < $len) {
                    $actual .= $tupla[$i + 1];
                    $i++;
                }
                continue;
            }
            if ($c === "'") {
                $enComilla = !$enComilla;
                $actual .= $c;
                continue;
            }
            if ($c === ',' && !$enComilla) {
                $partes[] = $actual;
                $actual = '';
                continue;
            }
            $actual .= $c;
        }
        $partes[] = $actual;

        if ($enComilla) {
            return null; // comillas sin cerrar
        }

        return $partes;
    }
}

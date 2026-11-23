<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class RespaldoBaseDatos
{
    const MAXIMO_BACKUPS = 10;

    protected $carpeta;
    protected $rutaMysqldump;

    public function __construct()
    {
        $this->carpeta = storage_path('backups');
        $this->rutaMysqldump = null;
    }

    public function crear()
    {
        $base = Config::get('database.connections.mysql');
        $base = is_array($base) ? $base : [];

        $host = isset($base['host']) ? $base['host'] : '127.0.0.1';
        $puerto = isset($base['port']) ? $base['port'] : '3306';
        $usuario = isset($base['username']) ? $base['username'] : 'root';
        $clave = isset($base['password']) ? $base['password'] : '';
        $nombre = isset($base['database']) ? $base['database'] : '';

        if ($nombre === '') {
            return $this->fallo('No hay base de datos configurada en database.php/.env');
        }

        $binario = $this->ubicarMysqldump();

        if ($binario === null) {
            return $this->fallo('No se encontró el programa mysqldump en el sistema');
        }

        if (!$this->prepararCarpeta()) {
            return $this->fallo('No se pudo crear la carpeta de respaldos en ' . $this->carpeta);
        }

        $archivo = $this->carpeta . DIRECTORY_SEPARATOR . $this->nombreArchivo($nombre);

        $comando = escapeshellarg($binario)
            . ' --host=' . escapeshellarg($host)
            . ' --port=' . escapeshellarg($puerto)
            . ' --user=' . escapeshellarg($usuario)
            . ' --password=' . escapeshellarg($clave)
            . ' --single-transaction --quick --skip-lock-tables'
            . ' --default-character-set=utf8 --routines --triggers'
            . ' --result-file=' . escapeshellarg($archivo)
            . ' ' . escapeshellarg($nombre);

        $codigo = 0;
        $salida = array();
        @exec($comando, $salida, $codigo);

        if ($codigo !== 0) {
            @unlink($archivo);
            $detalle = trim(implode(' ', $salida));
            return $this->fallo('mysqldump devolvió el código ' . $codigo . ($detalle !== '' ? ': ' . $detalle : ''));
        }

        if (!is_file($archivo) || filesize($archivo) === 0) {
            @unlink($archivo);
            return $this->fallo('El archivo de respaldo se generó vacío o no se creó');
        }

        $this->limpiarAntiguos();

        return array(
            'exito' => true,
            'archivo' => $archivo,
            'mensaje' => 'Respaldo generado correctamente',
        );
    }

    protected function prepararCarpeta()
    {
        if (is_dir($this->carpeta)) {
            return is_writable($this->carpeta);
        }

        try {
            File::makeDirectory($this->carpeta, 0755, true);
        } catch (\Exception $e) {
            Log::error('No se pudo crear la carpeta de respaldos: ' . $e->getMessage());
            return false;
        }

        return is_dir($this->carpeta) && is_writable($this->carpeta);
    }

    protected function nombreArchivo($base)
    {
        return 'respaldo_' . preg_replace('/[^A-Za-z0-9_]/', '', $base)
            . '_' . date('Y-m-d_H-i-s') . '.sql';
    }

    protected function ubicarMysqldump()
    {
        if ($this->rutaMysqldump !== null) {
            return $this->rutaMysqldump;
        }

        $candidatos = array(
            base_path('vendor/bin/mysqldump'),
            'C:/xampp/mysql/bin/mysqldump.exe',
            'C:/phpstudy_pro/Extensions/MySQL*/bin/mysqldump.exe',
            'C:/laragon/bin/mysql/*/bin/mysqldump.exe',
            '/opt/lampp/bin/mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
        );

        foreach ($candidatos as $candidato) {
            foreach (glob($candidato) as $ruta) {
                if (is_file($ruta)) {
                    $this->rutaMysqldump = $ruta;
                    return $this->rutaMysqldump;
                }
            }
        }

        $enPath = $this->buscarEnPath();

        $this->rutaMysqldump = $enPath;

        return $this->rutaMysqldump;
    }

    protected function buscarEnPath()
    {
        $comando = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'which';
        $codigo = 0;
        $salida = array();
        @exec($comando . ' mysqldump', $salida, $codigo);

        if ($codigo !== 0) {
            return null;
        }

        foreach ($salida as $ruta) {
            $ruta = trim($ruta);
            if ($ruta !== '' && is_file($ruta)) {
                return $ruta;
            }
        }

        return null;
    }

    protected function limpiarAntiguos()
    {
        $archivos = $this->listarBackups();

        if (count($archivos) <= self::MAXIMO_BACKUPS) {
            return;
        }

        foreach (array_slice($archivos, self::MAXIMO_BACKUPS) as $obsoleto) {
            @unlink($obsoleto['ruta']);
        }
    }

    public function listarBackups()
    {
        if (!is_dir($this->carpeta)) {
            return array();
        }

        $archivos = array();
        $patron = $this->carpeta . DIRECTORY_SEPARATOR . 'respaldo_*.sql';

        foreach (glob($patron) as $ruta) {
            if (!is_file($ruta)) {
                continue;
            }

            $archivos[] = array(
                'ruta' => $ruta,
                'nombre' => basename($ruta),
                'bytes' => filesize($ruta),
                'fecha' => filemtime($ruta),
            );
        }

        usort($archivos, function ($a, $b) {
            if ($a['fecha'] === $b['fecha']) {
                return strcmp($b['nombre'], $a['nombre']);
            }

            return $b['fecha'] - $a['fecha'];
        });

        return $archivos;
    }

    public function carpeta()
    {
        return $this->carpeta;
    }

    protected function fallo($mensaje)
    {
        Log::error('Respaldo de base de datos fallido: ' . $mensaje);

        return array(
            'exito' => false,
            'archivo' => null,
            'mensaje' => $mensaje,
        );
    }
}
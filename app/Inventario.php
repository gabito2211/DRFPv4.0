<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsignaSede;

class Inventario extends Model
{
    use AsignaSede;
    protected $table = 'inventario';
    
    protected $primaryKey = 'cota';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'titulo', 'autor', 'volumen', 'cota', 'categoria', 
        'ubicacion', 'ingreso', 'estado', 'observaciones', 'sede_id',
        'estanteria_id', 'dias_prestamo'
    ];
    
    protected $casts = [ 
        'volumen' => 'integer',
    ];

    // Historial de daños del libro
    public function historialDanos()
    {
        return $this->hasMany(HistorialDanoLibro::class, 'libro_id', 'cota')->orderBy('created_at', 'desc');
    }

    // Relación con los préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'libro_id', 'cota');
    }

    // Relación con los préstamos en sala (internos)
    public function prestamosInternos()
    {
        return $this->hasMany(PrestamoInterno::class, 'cota_libro', 'cota');
    }

    // Relación con la sede
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'ubicacion', 'id');
    }

    public function getNombreSedeAttribute()
    {
        if ($this->sede) {
            return $this->sede->biblioteca;
        }
        if ($this->sede_id) {
            $s = Sede::find($this->sede_id);
            return $s ? $s->biblioteca : null;
        }
        return null;
    }

    // Ubicación física del libro (estantería)
    public function estanteria()
    {
        return $this->belongsTo(Estanteria::class, 'estanteria_id');
    }

    // Verificar si está prestado a domicilio (pendiente por entregar)
    public function estaPrestadoADomicilio()
    {
        return $this->prestamos()->where('estatus', 'pendiente')->exists();
    }

    // Verificar si está prestado en sala (activo)
    public function estaPrestadoEnSala()
    {
        return $this->prestamosInternos()->where('estado', 'activo')->exists();
    }

    // Método para verificar si el libro está prestado
    public function estaPrestado()
    {
        return $this->prestamos()->where('estatus', 'pendiente')->exists();
    }
}
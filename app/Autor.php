<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    // NOTA: a propósito NO usa el trait AsignaSede. Los autores son un
    // catálogo compartido por todas las sedes (un mismo autor puede tener
    // libros en varias bibliotecas), así que no tiene sentido filtrarlos
    // ni duplicarlos por sede como sí ocurre con Inventario o Encargados.
    protected $table = 'autores';

    protected $fillable = [
        'nombre',
    ];

    // Relación con los libros del inventario (por nombre, igual que
    // Persona::prestamos() se relaciona con Prestamo por cédula en vez
    // de un id, ya que inventario.autor sigue siendo un campo de texto).
    public function libros()
    {
        return $this->hasMany(Inventario::class, 'autor', 'nombre');
    }
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mensajes de validación en español
    |--------------------------------------------------------------------------
    |
    | Antes la app tenía locale => 'en' y no existía resources/lang/es,
    | así que cualquier error de $this->validate() salía en inglés
    | ("The nombre field is required") en medio de una interfaz en español.
    |
    */

    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => 'El campo :attribute no es una URL válida.',
    'after'                => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo :attribute sólo debe contener letras.',
    'alpha_dash'           => 'El campo :attribute sólo debe contener letras, números y guiones.',
    'alpha_num'            => 'El campo :attribute sólo debe contener letras y números.',
    'array'                => 'El campo :attribute debe ser un arreglo.',
    'before'               => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'file'    => 'El campo :attribute debe pesar entre :min y :max kilobytes.',
        'string'  => 'El campo :attribute debe tener entre :min y :max caracteres.',
        'array'   => 'El campo :attribute debe tener entre :min y :max elementos.',
    ],
    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'            => 'La confirmación de :attribute no coincide.',
    'date'                 => 'El campo :attribute no es una fecha válida.',
    'date_format'          => 'El campo :attribute no corresponde al formato :format.',
    'different'            => 'Los campos :attribute y :other deben ser distintos.',
    'digits'               => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between'       => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'email'                => 'El campo :attribute debe ser una dirección de correo válida.',
    'exists'               => 'El :attribute seleccionado no es válido.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute es obligatorio.',
    'image'                => 'El campo :attribute debe ser una imagen.',
    'in'                   => 'El :attribute seleccionado no es válido.',
    'integer'              => 'El campo :attribute debe ser un número entero.',
    'ip'                   => 'El campo :attribute debe ser una dirección IP válida.',
    'json'                 => 'El campo :attribute debe ser una cadena JSON válida.',
    'max'                  => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'file'    => 'El campo :attribute no debe pesar más de :max kilobytes.',
        'string'  => 'El campo :attribute no debe tener más de :max caracteres.',
        'array'   => 'El campo :attribute no debe tener más de :max elementos.',
    ],
    'mimes'                => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'file'    => 'El campo :attribute debe pesar al menos :min kilobytes.',
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'not_in'               => 'El :attribute seleccionado no es válido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El formato del campo :attribute no es válido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values está presente.',
    'same'                 => 'Los campos :attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file'    => 'El campo :attribute debe pesar :size kilobytes.',
        'string'  => 'El campo :attribute debe tener :size caracteres.',
        'array'   => 'El campo :attribute debe contener :size elementos.',
    ],
    'string'               => 'El campo :attribute debe ser una cadena de texto.',
    'unique'               => 'El :attribute ya ha sido registrado.',
    'url'                  => 'El formato del campo :attribute no es válido.',

    /*
    |--------------------------------------------------------------------------
    | Nombres de atributos personalizados
    |--------------------------------------------------------------------------
    |
    | Para que el mensaje diga "El campo Cédula..." en vez de
    | "El campo id-card...", usando los mismos nombres de campo
    | que ya usan los formularios de este sistema.
    |
    */

    'attributes' => [
        'id-card'              => 'cédula',
        'fullname'             => 'nombre y apellido',
        'nombre'               => 'nombre',
        'sexo'                 => 'sexo',
        'gender'               => 'sexo',
        'telefono'             => 'teléfono',
        'telefono2'            => 'teléfono secundario',
        'gmail'                => 'correo electrónico',
        'email'                => 'correo electrónico',
        'correo'               => 'correo electrónico',
        'comunidad'            => 'comunidad',
        'direccion'            => 'dirección',
        'department'           => 'departamento',
        'sala_id'              => 'sala',
        'institucion'          => 'institución',
        'responsable'          => 'responsable',
        'encargado'            => 'encargado',
        'cantidad'             => 'cantidad',
        'fecha_visita'         => 'fecha de la visita',
        'hora'                 => 'hora de la visita',
        'tipo_recorrido'       => 'tipo de recorrido',
        'biblioteca'           => 'nombre de la biblioteca',
        'cedula'               => 'cédula',
        'titulo'               => 'título',
        'autor'                => 'autor',
        'cota'                 => 'cota',
        'categoria'            => 'categoría',
        'ubicacion'            => 'ubicación',
        'ingreso'              => 'fecha de ingreso',
        'estado'               => 'estado',
        'observaciones'        => 'observaciones',
        'volumen'              => 'volumen',
        'lector_id'            => 'lector',
        'libro_id'             => 'libro',
        'fecha_salida'         => 'fecha de salida',
        'fecha_devolucion'     => 'fecha de devolución',
        'condiciones_salida'   => 'condiciones de salida',
        'condiciones_regreso'  => 'condiciones de regreso',
        'estatus'              => 'estatus',
        'num-carnet'           => 'número de carnet',
        'profesion'            => 'profesión',
        'exp'                  => 'fecha de emisión',
        'venc'                 => 'fecha de vencimiento',
        'foto'                 => 'foto',
        'name'                 => 'nombre',
        'password'             => 'contraseña',
    ],

];

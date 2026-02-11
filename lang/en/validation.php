<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mensajes de Validación
    |--------------------------------------------------------------------------
    */

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'required' => 'El campo :attribute es obligatorio.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    
    // ESTE ES EL QUE BUSCAS PARA EL EMAIL:
    'unique' => 'El :attribute ya ha sido registrado por otro usuario.',

    /*
    |--------------------------------------------------------------------------
    | Nombres de Atributos Personalizados
    |--------------------------------------------------------------------------
    | Aquí defines que ":attribute" se lea como "correo electrónico" en vez de "email"
    */

    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'name' => 'nombre',
    ],
];
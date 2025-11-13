<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvangelioDiario extends Model
{
    use HasFactory;

    protected $table = 'evangelio_diario';

    protected $fillable = [
        'contenido',
        'referencia',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Obtener el evangelio del día actual
    public static function obtenerEvangelioHoy()
    {
        return self::where('fecha', today())->first();
    }
}
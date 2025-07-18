<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'referencia',
        'nombre',
        'descripcion',
        'fecha_creacion',
        'fecha_completada',
        'estado'
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
        'fecha_completada' => 'date',
    ];

    /**
     * Obtener el estado formateado
     */
    public function obtenerEstadoFormateado(): string
    {
        return match($this->estado) {
            'sin_iniciar' => 'Sin iniciar',
            'en_proceso' => 'En proceso',
            'completada' => 'Completada',
            'anulada' => 'Anulada',
            default => 'Desconocido',
        };
    }

    /**
     * Obtener el color del estado para Bootstrap
     */
    public function obtenerColorEstado(): string
    {
        return match($this->estado) {
            'sin_iniciar' => 'secondary',
            'en_proceso' => 'primary',
            'completada' => 'success',
            'anulada' => 'danger',
            default => 'light',
        };
    }
}
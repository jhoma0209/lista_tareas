<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'referencia',
        'nombre',
        'descripcion',
        'estado',
        'fecha_creacion',
        'fecha_completada',
        'user_id'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_completada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function obtenerColorEstado(): string
    {
        return match($this->estado) {
            'sin_iniciar' => 'secondary',
            'en_proceso' => 'warning',
            'completada' => 'success',
            'anulada' => 'danger',
            default => 'secondary',
        };
    }

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

    public function obtenerIconoEstado(): string
    {
        return match($this->estado) {
            'sin_iniciar' => 'clock',
            'en_proceso' => 'spinner',
            'completada' => 'check-circle',
            'anulada' => 'ban',
            default => 'question-circle',
        };
    }
}
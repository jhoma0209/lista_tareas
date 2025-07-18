<?php

namespace App\Repositories;

use App\Models\Tarea;
use Illuminate\Support\Str;

class TareaRepository
{
    /**
     * Obtener todas las tareas
     */
    public function obtenerTodas()
    {
        return Tarea::latest()->get();
    }

    /**
     * Obtener tareas por estado
     */
    public function obtenerPorEstado(string $estado)
    {
        return Tarea::where('estado', $estado)->latest()->get();
    }

    /**
     * Obtener tareas en proceso
     */
    public function obtenerEnProceso()
    {
        return $this->obtenerPorEstado('en_proceso');
    }

    /**
     * Crear una nueva tarea
     */
    public function crearTarea(array $datos)
    {
        $datos['referencia'] = Str::uuid();
        $datos['fecha_creacion'] = now();
        
        return Tarea::create($datos);
    }

    /**
     * Actualizar una tarea
     */
    public function actualizarTarea(Tarea $tarea, array $datos)
    {
        if (isset($datos['estado'])) {
            $datos['fecha_completada'] = $datos['estado'] === 'completada' ? now() : null;
        }
        
        return $tarea->update($datos);
    }

    /**
     * Eliminar una tarea
     */
    public function eliminarTarea(Tarea $tarea)
    {
        return $tarea->delete();
    }

    /**
     * Filtrar tareas
     */
    public function filtrarTareas(array $filtros)
    {
        $query = Tarea::query();
        
        if (isset($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }
        
        if (isset($filtros['fecha_desde'])) {
            $query->where('fecha_creacion', '>=', $filtros['fecha_desde']);
        }
        
        if (isset($filtros['fecha_hasta'])) {
            $query->where('fecha_creacion', '<=', $filtros['fecha_hasta']);
        }
        
        if (isset($filtros['completadas_desde'])) {
            $query->where('fecha_completada', '>=', $filtros['completadas_desde']);
        }
        
        if (isset($filtros['completadas_hasta'])) {
            $query->where('fecha_completada', '<=', $filtros['completadas_hasta']);
        }
        
        return $query->latest()->get();
    }
}
<?php

namespace App\Services;

use App\Repositories\TareaRepository;

class TareaService
{
    protected $tareaRepository;

    public function __construct(TareaRepository $tareaRepository)
    {
        $this->tareaRepository = $tareaRepository;
    }

    /**
     * Obtener todas las tareas
     */
    public function obtenerTodasLasTareas()
    {
        return $this->tareaRepository->obtenerTodas();
    }

    /**
     * Obtener tareas en proceso
     */
    public function obtenerTareasEnProceso()
    {
        return $this->tareaRepository->obtenerEnProceso();
    }

    /**
     * Crear una nueva tarea
     */
    public function crearTarea(array $datos)
    {
        return $this->tareaRepository->crearTarea($datos);
    }

    /**
     * Actualizar una tarea
     */
    public function actualizarTarea($tarea, array $datos)
    {
        return $this->tareaRepository->actualizarTarea($tarea, $datos);
    }

    /**
     * Eliminar una tarea
     */
    public function eliminarTarea($tarea)
    {
        return $this->tareaRepository->eliminarTarea($tarea);
    }

    /**
     * Filtrar tareas
     */
    public function filtrarTareas(array $filtros)
    {
        return $this->tareaRepository->filtrarTareas($filtros);
    }
}
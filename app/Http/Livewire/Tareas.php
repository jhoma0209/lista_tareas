<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\TareaService;
use App\Models\Tarea;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Tareas extends Component
{    
    public $tareas = [];
    public $tareaSeleccionada = null;
    public $mostrarModal = false;
    public $mostrarFiltros = false;
    public $filtros = [
        'estado' => '',
        'fecha_desde' => '',
        'fecha_hasta' => '',
        'completadas_desde' => '',
        'completadas_hasta' => ''
    ];

    protected $tareaService;

    public function boot()
    {
        $this->tareaService = app(TareaService::class);
    }

    public function mount()
    {
        $this->cargarTareas();
    }

    public function cargarTareas()
    {
        $this->tareas = collect($this->tareaService->obtenerTodasLasTareas());
    }

    public function cargarTareasEnProceso()
    {
        $this->tareas = collect($this->tareaService->obtenerTareasEnProceso());
    }

    public function abrirModalCrear()
    {
        $this->tareaSeleccionada = new Tarea();
        $this->mostrarModal = true;
    }

    public function abrirModalEditar($tareaId)
    {
        $this->tareaSeleccionada = Tarea::find($tareaId);
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function guardarTarea()
    {
        $datos = $this->validate([
            'tareaSeleccionada.nombre' => 'required|string|max:255',
            'tareaSeleccionada.descripcion' => 'required|string',
            'tareaSeleccionada.estado' => 'required|in:sin_iniciar,en_proceso,completada,anulada',
        ]);

        if ($this->tareaSeleccionada->id) {
            $this->tareaService->actualizarTarea($this->tareaSeleccionada, $datos['tareaSeleccionada']);
            session()->flash('mensaje', 'Tarea actualizada correctamente');
        } else {
            $this->tareaService->crearTarea($datos['tareaSeleccionada']);
            session()->flash('mensaje', 'Tarea creada correctamente');
        }

        $this->cerrarModal();
        $this->cargarTareas();
    }

    public function cambiarEstado($tareaId, $estado)
    {
        $tarea = Tarea::find($tareaId);
        if ($tarea) {
            $this->tareaService->actualizarTarea($tarea, ['estado' => $estado]);
            $this->cargarTareas();
            session()->flash('mensaje', 'Estado actualizado correctamente');
        }
    }

    public function eliminarTarea($tareaId)
    {
        $tarea = Tarea::find($tareaId);
        if ($tarea) {
            $this->tareaService->eliminarTarea($tarea);
            $this->cargarTareas();
            session()->flash('mensaje', 'Tarea eliminada correctamente');
        }
    }

    public function aplicarFiltros()
    {
        $filtrosLimpios = array_filter($this->filtros);
        $this->tareas = collect($this->tareaService->filtrarTareas($filtrosLimpios));
        $this->mostrarFiltros = false;
    }

    public function limpiarFiltros()
    {
        $this->reset('filtros');
        $this->cargarTareas();
        $this->mostrarFiltros = false;
    }

    public function obtenerIconoEstado($estado): string
    {
        return match($estado) {
            'sin_iniciar' => 'clock',
            'en_proceso' => 'spinner',
            'completada' => 'check-circle',
            'anulada' => 'ban',
            default => 'question-circle',
        };
    }

    public function obtenerColorEstado($estado): string
    {
        return match($estado) {
            'sin_iniciar' => 'secondary',
            'en_proceso' => 'warning',
            'completada' => 'success',
            'anulada' => 'danger',
            default => 'secondary',
        };
    }

    public function obtenerNombreEstado($estado): string
    {
        return match($estado) {
            'sin_iniciar' => 'Sin iniciar',
            'en_proceso' => 'En proceso',
            'completada' => 'Completada',
            'anulada' => 'Anulada',
            default => 'Desconocido',
        };
    }

    public function toggleFiltros()
    {
        $this->mostrarFiltros = !$this->mostrarFiltros;
    }

    public function render()
    {
        return view('livewire.tareas');
    }
}
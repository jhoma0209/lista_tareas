<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\TareaService;
use App\Models\Tarea;
use Livewire\Attributes\Layout;

class Tareas extends Component
{    
    #[Layout('layouts.app')] 
    
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

    public function __construct()
    {
        $this->tareaService = app(TareaService::class);
    }

    public function mount()
    {
        $this->cargarTareas();
    }

    public function cargarTareas()
    {
        $this->tareas = $this->tareaService->obtenerTodasLasTareas();
    }

    public function cargarTareasEnProceso()
    {
        $this->tareas = $this->tareaService->obtenerTareasEnProceso();
    }

    public function abrirModalCrear()
    {
        $this->tareaSeleccionada = null;
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
        $this->tareaService->actualizarTarea($tarea, ['estado' => $estado]);
        $this->cargarTareas();
    }

    public function eliminarTarea($tareaId)
    {
        $tarea = Tarea::find($tareaId);
        $this->tareaService->eliminarTarea($tarea);
        $this->cargarTareas();
    }

    public function aplicarFiltros()
    {
        $this->tareas = $this->tareaService->filtrarTareas(array_filter($this->filtros));
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

    public function toggleFiltros()
    {
        $this->mostrarFiltros = !$this->mostrarFiltros;
    }

    public function render()
    {
        return view('livewire.tareas');
    }
}
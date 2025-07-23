<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\TareaService;
use App\Models\Tarea;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]

class Tareas extends Component
{
    use WithPagination;

    public $tareas = [];
    public $users;
    public $tareaSeleccionada = [
        'nombre' => '',
        'descripcion' => '',
        'estado' => 'sin_iniciar'
    ];
    public $mostrarModal = false;
    public $mostrarFiltros = false;
    public $filtros = [
        'estado' => '',
        'fecha_desde' => '',
        'fecha_hasta' => '',
    ];

    protected $tareaService;

    public function boot(TareaService $tareaService)
    {
        $this->tareaService = $tareaService;
    }

    public function mount()
    {
        $this->users = User::all();
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
        $this->tareaSeleccionada = [
            'nombre' => '',
            'descripcion' => '',
            'estado' => 'sin_iniciar'
        ];
        $this->mostrarModal = true;
    }

    public function abrirModalEditar($tareaId)
    {
        $tarea = Tarea::find($tareaId);
        $this->tareaSeleccionada = [
            'id' => $tarea->id,
            'nombre' => $tarea->nombre,
            'descripcion' => $tarea->descripcion,
            'estado' => $tarea->estado
        ];
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
        $validated = $this->validate([
            'tareaSeleccionada.nombre' => 'required|string|max:255',
            'tareaSeleccionada.descripcion' => 'required|string',
            'tareaSeleccionada.estado' => 'required|in:sin_iniciar,en_proceso,completada,anulada',
            'tareaSeleccionada.user_id' => 'nullable|exists:users,id',
        ]);

        if (isset($this->tareaSeleccionada['id'])) {
            $tarea = Tarea::find($this->tareaSeleccionada['id']);
            $this->tareaService->actualizarTarea($tarea, $validated['tareaSeleccionada']);
            session()->flash('mensaje', 'Tarea actualizada correctamente');
        } else {
            $this->tareaService->crearTarea($validated['tareaSeleccionada']);
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
        $filtrosLimpios = array_filter($this->filtros, function($value) {
            return $value !== null && $value !== '';
        });
        
        $this->tareas = $this->tareaService->filtrarTareas($filtrosLimpios);
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
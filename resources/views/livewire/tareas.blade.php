<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Lista de Tareas</h3>
            <div>
                <button class="btn btn-light btn-sm me-2" wire:click="cargarTareasEnProceso">
                    <i class="fas fa-tasks"></i> Tareas en Proceso
                </button>
                <button class="btn btn-light btn-sm me-2" wire:click="cargarTareas">
                    <i class="fas fa-list"></i> Todas las Tareas
                </button>
                <button class="btn btn-light btn-sm me-2" wire:click="mostrarFiltros = !mostrarFiltros">
                    <i class="fas fa-filter"></i> Filtros
                </button>
                <button class="btn btn-success btn-sm" wire:click="abrirModalCrear">
                    <i class="fas fa-plus"></i> Nueva Tarea
                </button>
            </div>
        </div>
        
        <!-- Filtros -->
        @if($mostrarFiltros)
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select" wire:model="filtros.estado">
                        <option value="">Todos</option>
                        <option value="sin_iniciar">Sin iniciar</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="completada">Completada</option>
                        <option value="anulada">Anulada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha creación desde</label>
                    <input type="date" class="form-control" wire:model="filtros.fecha_desde">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha creación hasta</label>
                    <input type="date" class="form-control" wire:model="filtros.fecha_hasta">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Completadas desde</label>
                    <input type="date" class="form-control" wire:model="filtros.completadas_desde">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Completadas hasta</label>
                    <input type="date" class="form-control" wire:model="filtros.completadas_hasta">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary btn-sm" wire:click="aplicarFiltros">
                        <i class="fas fa-check"></i> Aplicar
                    </button>
                    <button class="btn btn-secondary btn-sm ms-2" wire:click="limpiarFiltros">
                        <i class="fas fa-broom"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
        @endif
        
        <div class="card-body">
            @if(session()->has('mensaje'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('mensaje') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(count($tareas) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha Creación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tareas as $tarea)
                                <tr>
                                    <td>{{ $tarea->referencia }}</td>
                                    <td>{{ $tarea->nombre }}</td>
                                    <td>{{ Str::limit($tarea->descripcion, 50) }}</td>
                                    <td>{{ $tarea->fecha_creacion->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $tarea->obtenerColorEstado() }}">
                                            {{ $tarea->obtenerEstadoFormateado() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-primary" 
                                                    wire:click="abrirModalEditar({{ $tarea->id }})"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           wire:click="cambiarEstado({{ $tarea->id }}, 'sin_iniciar')">
                                                            Sin iniciar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           wire:click="cambiarEstado({{ $tarea->id }}, 'en_proceso')">
                                                            En proceso
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           wire:click="cambiarEstado({{ $tarea->id }}, 'completada')">
                                                            Completada
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           wire:click="cambiarEstado({{ $tarea->id }}, 'anulada')">
                                                            Anulada
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            
                                            <button class="btn btn-danger" 
                                                    wire:click="eliminarTarea({{ $tarea->id }})"
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No hay tareas registradas. ¡Crea una nueva tarea!
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal para crear/editar tarea -->
    @if($mostrarModal)
        <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $tareaSeleccionada ? 'Editar Tarea' : 'Nueva Tarea' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="cerrarModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="guardarTarea">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" 
                                       wire:model="tareaSeleccionada.nombre">
                                @error('tareaSeleccionada.nombre') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" rows="3"
                                          wire:model="tareaSeleccionada.descripcion"></textarea>
                                @error('tareaSeleccionada.descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" wire:model="tareaSeleccionada.estado">
                                    <option value="sin_iniciar">Sin iniciar</option>
                                    <option value="en_proceso">En proceso</option>
                                    <option value="completada">Completada</option>
                                    <option value="anulada">Anulada</option>
                                </select>
                                @error('tareaSeleccionada.estado') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2" wire:click="cerrarModal">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .table-hover tbody tr {
            transition: all 0.2s;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateX(2px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            // Animaciones para los modales
            Livewire.on('mostrarModal', () => {
                const modal = document.querySelector('.modal');
                modal.classList.add('fade-in');
            });
        });
    </script>
@endpush
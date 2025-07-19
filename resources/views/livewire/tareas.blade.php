<div class="pt-4">
    <div class="card shadow-sm">
        <div class="card-header text-white bg-dark">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-cog fa-fw"></i>Gestión de Tareas
                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-light me-2" wire:click="toggleFiltros">
                        <i class="fas fa-filter me-1"></i>Filtros
                    </button>
                    <button class="btn btn-sm btn-success" wire:click="abrirModalCrear">
                        <i class="fas fa-plus me-1"></i>Nueva
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Filtros -->
        @if($mostrarFiltros)
        <div class="card-body border-bottom bg-light">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select form-select-sm" wire:model.live="filtros.estado">
                        <option value="">Todos</option>
                        <option value="sin_iniciar">Sin iniciar</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="completada">Completada</option>
                        <option value="anulada">Anulada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha creación desde</label>
                    <input type="date" class="form-control form-control-sm" wire:model.live="filtros.fecha_desde">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha creación hasta</label>
                    <input type="date" class="form-control form-control-sm" wire:model.live="filtros.fecha_hasta">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-sm btn-primary me-2" wire:click="aplicarFiltros">
                        <i class="fas fa-check me-1"></i>Aplicar
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" wire:click="limpiarFiltros">
                        <i class="fas fa-broom me-1"></i>Limpiar
                    </button>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Contenido Principal -->
        <div class="card-body">
            @if(session()->has('mensaje'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('mensaje') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Barra de Acciones -->
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <button class="btn btn-sm btn-outline-primary me-2" wire:click="cargarTareasEnProceso">
                        <i class="fas fa-spinner me-1"></i>En Proceso
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" wire:click="cargarTareas">
                        <i class="fas fa-list me-1"></i>Todas
                    </button>
                </div>
                <div class="text-muted">
                    Mostrando {{ count($tareas) }} tareas
                </div>
            </div>
            
            <!-- Lista de Tareas -->
            @if(count($tareas) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Referencia</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tareas as $tarea)
                                <tr>
                                    <td>{{ $tarea->referencia}}</td>
                                    <td>{{ $tarea->nombre }}</td>
                                    <td>{{ Str::limit($tarea->descripcion, 40) }}</td>
                                    <td>{{ $tarea->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $this->obtenerColorEstado($tarea->estado) }}">
                                            <i class="fas fa-{{ $this->obtenerIconoEstado($tarea->estado) }} me-1"></i>
                                            {{ $this->obtenerNombreEstado($tarea->estado) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" 
                                                    wire:click="abrirModalEditar({{ $tarea->id }})"
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @foreach(['sin_iniciar', 'en_proceso', 'completada', 'anulada'] as $estado)
                                                        @if($tarea->estado != $estado)
                                                            <li>
                                                                <a class="dropdown-item" href="#" 
                                                                   wire:click="cambiarEstado({{ $tarea->id }}, '{{ $estado }}')">
                                                                    <i class="fas fa-{{ $this->obtenerIconoEstado($estado) }} me-2 text-{{ $this->obtenerColorEstado($estado) }}"></i>
                                                                    {{ $this->obtenerNombreEstado($estado) }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                            
                                            <button class="btn btn-outline-danger" 
                                                    wire:click="eliminarTarea({{ $tarea->id }})"
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>No se encontraron tareas. ¡Crea una nueva para comenzar!
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal para crear/editar tarea -->
    @if($mostrarModal)
        <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-{{ isset($tareaSeleccionada['id']) ? 'edit' : 'plus' }} me-2"></i>
                            {{ isset($tareaSeleccionada['id']) ? 'Editar Tarea' : 'Nueva Tarea' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cerrarModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="guardarTarea">
                            <div class="row g-3">

                                <div class="col-md-12">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" 
                                           wire:model="tareaSeleccionada.nombre" placeholder="Nombre de la tarea">
                                    @error('tareaSeleccionada.nombre') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" rows="3"
                                              wire:model="tareaSeleccionada.descripcion" placeholder="Descripción detallada"></textarea>
                                    @error('tareaSeleccionada.descripcion') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" id="estado" wire:model="tareaSeleccionada.estado">
                                        @foreach(['sin_iniciar' => 'Sin iniciar', 'en_proceso' => 'En proceso', 'completada' => 'Completada', 'anulada' => 'Anulada'] as $valor => $texto)
                                            <option value="{{ $valor }}">{{ $texto }}</option>
                                        @endforeach
                                    </select>
                                    @error('tareaSeleccionada.estado') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-outline-secondary" wire:click="cerrarModal">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', function() {
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Cerrar modal al presionar ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                Livewire.dispatch('cerrarModal');
            }
        });
    });
    
    // Actualizar tooltips cuando Livewire actualice el DOM
    document.addEventListener('livewire:update', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            if (!tooltipTriggerEl._tooltip) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            }
        });
    });
</script>
@endpush
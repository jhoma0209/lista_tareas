@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="pt-4">
    <div class="card shadow-sm">
        <div class="card-header text-white bg-dark">
            <h5 class="mb-0">
                <i class="fas fa-home me-2"></i>Bienvenido al Gestor de Tareas
            </h5>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center py-4">
                    <h3 class="mb-4">
                        <i class="fas fa-tasks text-dark me-2"></i>Organiza tus tareas eficientemente
                    </h3>
                    <a href="{{ route('tareas.index') }}" class="btn btn-dark btn-lg">
                        <i class="fas fa-arrow-right me-2"></i>Comenzar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
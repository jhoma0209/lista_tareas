<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lista de Tareas')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-light">
    <div class="d-flex flex-column min-vh-100">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Inicio
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tareas.index') }}">
                                <i class="fas fa-list-check me-1"></i>Tareas
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="container py-4 flex-grow-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-white py-3 mt-auto">
            <div class="container text-center">
                <small>Sistema de Gestión de Tareas</small>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Alpine.js para interactividad -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
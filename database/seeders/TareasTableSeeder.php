<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarea;
use Carbon\Carbon;

class TareasTableSeeder extends Seeder
{
    public function run(): void
    {
        $tareas = [
            [
                'nombre' => 'Configurar entorno de desarrollo',
                'descripcion' => 'Instalar Laravel, Livewire y configurar la base de datos',
                'fecha_creacion' => Carbon::now()->subDays(5),
                'estado' => 'completada',
                'fecha_completada' => Carbon::now()->subDays(3),
            ],
            [
                'nombre' => 'Diseñar interfaz de usuario',
                'descripcion' => 'Crear las vistas principales con Bootstrap',
                'fecha_creacion' => Carbon::now()->subDays(4),
                'estado' => 'en_proceso',
            ],
            [
                'nombre' => 'Implementar lógica de negocio',
                'descripcion' => 'Desarrollar los servicios y repositorios para las tareas',
                'fecha_creacion' => Carbon::now()->subDays(3),
                'estado' => 'en_proceso',
            ],
            [
                'nombre' => 'Crear migraciones',
                'descripcion' => 'Definir la estructura de la base de datos para las tareas',
                'fecha_creacion' => Carbon::now()->subDays(2),
                'estado' => 'completada',
                'fecha_completada' => Carbon::now()->subDays(1),
            ],
            [
                'nombre' => 'Escribir pruebas unitarias',
                'descripcion' => 'Crear tests para los componentes principales',
                'fecha_creacion' => Carbon::now()->subDays(1),
                'estado' => 'sin_iniciar',
            ],
            [
                'nombre' => 'Desplegar aplicación',
                'descripcion' => 'Configurar servidor y subir la aplicación a producción',
                'fecha_creacion' => Carbon::now(),
                'estado' => 'sin_iniciar',
            ],
            [
                'nombre' => 'Documentar API',
                'descripcion' => 'Crear documentación para los endpoints de la API',
                'fecha_creacion' => Carbon::now()->subDays(2),
                'estado' => 'anulada',
            ],
        ];

        foreach ($tareas as $tarea) {
            Tarea::create([
                'referencia' => \Illuminate\Support\Str::uuid(),
                'nombre' => $tarea['nombre'],
                'descripcion' => $tarea['descripcion'],
                'fecha_creacion' => $tarea['fecha_creacion'],
                'estado' => $tarea['estado'],
                'fecha_completada' => $tarea['fecha_completada'] ?? null,
            ]);
        }
    }
}
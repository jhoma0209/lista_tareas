<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('referencia')->unique();
            $table->string('nombre');
            $table->text('descripcion');
            $table->date('fecha_creacion');
            $table->date('fecha_completada')->nullable();
            $table->enum('estado', ['sin_iniciar', 'en_proceso', 'completada', 'anulada']);
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
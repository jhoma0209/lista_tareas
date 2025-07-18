<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Tareas;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tareas', Tareas::class)->name('tareas.index');
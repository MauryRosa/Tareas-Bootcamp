<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/consultas/{metodo}', [ConsultaController::class, 'ejecutarConsulta']);

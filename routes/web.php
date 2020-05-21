<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Vista por defecto
Route::get('/', function () {
    return redirect('home');
});

// Asignamos el login auth a las vistas, excepto welcome
Auth::routes();

// Endpoint visual sobre control de usuarios agregados
Route::get('/home', 'HomeController@index')->name('home');

// Endpoint visual para subida de usuarios
Route::get('/upload', 'UploadResourcesController@index')->name('upload');

// Url correspondiente de la API para realizar peticiones referentes al registro de usuarios usando archivos
Route::apiResource('/api/users', 'ApiUploadResourceController');
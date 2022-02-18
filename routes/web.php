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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth']], function() {
    
    Route::get('/empresas/index', [App\Http\Controllers\EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/create', [App\Http\Controllers\EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('/empresas/store', [App\Http\Controllers\EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('/empresas/show/{id}', [App\Http\Controllers\EmpresaController::class, 'show'])->name('empresas.show');
    Route::get('/empresas/edit/{id}', [App\Http\Controllers\EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::post('/empresas/update/{id}', [App\Http\Controllers\EmpresaController::class, 'update'])->name('empresas.update');

    Route::get('/usuarios/index', [App\Http\Controllers\UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [App\Http\Controllers\UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/store', [App\Http\Controllers\UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/show/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('usuarios.edit');
    Route::post('/usuarios/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('usuarios.update');

});
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

    Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
        Route::get('/empresas/index', [App\Http\Controllers\EmpresaController::class, 'index'])->name('admin.empresas.index');
        Route::get('/empresas/create', [App\Http\Controllers\EmpresaController::class, 'create'])->name('admin.empresas.create');
        Route::post('/empresas/store', [App\Http\Controllers\EmpresaController::class, 'store'])->name('admin.empresas.store');
        Route::get('/empresas/show/{id}', [App\Http\Controllers\EmpresaController::class, 'show'])->name('admin.empresas.show');
        Route::get('/empresas/edit/{id}', [App\Http\Controllers\EmpresaController::class, 'edit'])->name('admin.empresas.edit');
        Route::post('/empresas/update/{id}', [App\Http\Controllers\EmpresaController::class, 'update'])->name('admin.empresas.update');

        Route::get('/usuarios/index', [App\Http\Controllers\UserController::class, 'index'])->name('admin.usuarios.index');
        Route::get('/usuarios/create', [App\Http\Controllers\UserController::class, 'create'])->name('admin.usuarios.create');
        Route::post('/usuarios/store', [App\Http\Controllers\UserController::class, 'store'])->name('admin.usuarios.store');
        Route::get('/usuarios/show/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('admin.usuarios.show');
        Route::get('/usuarios/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('admin.usuarios.edit');
        Route::post('/usuarios/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.usuarios.update');

        Route::get('/razones-sociales/index', [App\Http\Controllers\RazonSocialController::class, 'index'])->name('admin.razonesSociales.index');
        Route::get('/razones-sociales/create', [App\Http\Controllers\RazonSocialController::class, 'create'])->name('admin.razonesSociales.create');
        Route::post('/razones-sociales/store', [App\Http\Controllers\RazonSocialController::class, 'store'])->name('admin.razonesSociales.store');
        Route::get('/razones-sociales/show/{id}', [App\Http\Controllers\RazonSocialController::class, 'show'])->name('admin.razonesSociales.show');
        Route::get('/razones-sociales/edit/{id}', [App\Http\Controllers\RazonSocialController::class, 'edit'])->name('admin.razonesSociales.edit');
        Route::post('/razones-sociales/update/{id}', [App\Http\Controllers\RazonSocialController::class, 'update'])->name('admin.razonesSociales.update');

        Route::get('/gestiones/index', [App\Http\Controllers\GestionController::class, 'index'])->name('admin.gestiones.index');
        Route::get('/gestiones/create', [App\Http\Controllers\GestionController::class, 'create'])->name('admin.gestiones.create');
        Route::post('/gestiones/store', [App\Http\Controllers\GestionController::class, 'store'])->name('admin.gestiones.store');
        Route::get('/gestiones/show/{id}', [App\Http\Controllers\GestionController::class, 'show'])->name('admin.gestiones.show');
        Route::get('/gestiones/edit/{id}', [App\Http\Controllers\GestionController::class, 'edit'])->name('admin.gestiones.edit');
        Route::post('/gestiones/update/{id}', [App\Http\Controllers\GestionController::class, 'update'])->name('admin.gestiones.update');

        Route::get('/reportes/index', [App\Http\Controllers\ReporteController::class, 'index'])->name('admin.reportes.index');
        Route::get('/reportes/create', [App\Http\Controllers\ReporteController::class, 'create'])->name('admin.reportes.create');
        Route::post('/reportes/store', [App\Http\Controllers\ReporteController::class, 'store'])->name('admin.reportes.store');
        Route::get('/reportes/show/{id}', [App\Http\Controllers\ReporteController::class, 'show'])->name('admin.reportes.show');
        Route::get('/reportes/edit/{id}', [App\Http\Controllers\ReporteController::class, 'edit'])->name('admin.reportes.edit');
        Route::post('/reportes/update/{id}', [App\Http\Controllers\ReporteController::class, 'update'])->name('admin.reportes.update');
    });
    


});
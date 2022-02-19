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
// Route::get('/prueba', [App\Http\Controllers\HomeController::class, 'prueba'])->name('prueba');


Route::group(['middleware' => ['auth']], function() {
    // ADMIN
    Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function() {
        Route::get('/empresas/index', [App\Http\Controllers\EmpresaController::class, 'admin_index'])->name('admin.empresas.index');
        Route::get('/empresas/create', [App\Http\Controllers\EmpresaController::class, 'admin_create'])->name('admin.empresas.create');
        Route::post('/empresas/store', [App\Http\Controllers\EmpresaController::class, 'admin_store'])->name('admin.empresas.store');
        Route::get('/empresas/show/{id}', [App\Http\Controllers\EmpresaController::class, 'admin_show'])->name('admin.empresas.show');
        Route::get('/empresas/edit/{id}', [App\Http\Controllers\EmpresaController::class, 'admin_edit'])->name('admin.empresas.edit');
        Route::post('/empresas/update/{id}', [App\Http\Controllers\EmpresaController::class, 'admin_update'])->name('admin.empresas.update');

        Route::get('/usuarios/index', [App\Http\Controllers\UserController::class, 'admin_index'])->name('admin.usuarios.index');
        Route::get('/usuarios/create', [App\Http\Controllers\UserController::class, 'admin_create'])->name('admin.usuarios.create');
        Route::post('/usuarios/store', [App\Http\Controllers\UserController::class, 'admin_store'])->name('admin.usuarios.store');
        Route::get('/usuarios/show/{id}', [App\Http\Controllers\UserController::class, 'admin_show'])->name('admin.usuarios.show');
        Route::get('/usuarios/edit/{id}', [App\Http\Controllers\UserController::class, 'admin_edit'])->name('admin.usuarios.edit');
        Route::post('/usuarios/update/{id}', [App\Http\Controllers\UserController::class, 'admin_update'])->name('admin.usuarios.update');

        Route::get('/razones-sociales/index', [App\Http\Controllers\RazonSocialController::class, 'admin_index'])->name('admin.razones-sociales.index');
        Route::get('/razones-sociales/create', [App\Http\Controllers\RazonSocialController::class, 'admin_create'])->name('admin.razones-sociales.create');
        Route::post('/razones-sociales/store', [App\Http\Controllers\RazonSocialController::class, 'admin_store'])->name('admin.razones-sociales.store');
        Route::get('/razones-sociales/show/{id}', [App\Http\Controllers\RazonSocialController::class, 'admin_show'])->name('admin.razones-sociales.show');
        Route::get('/razones-sociales/edit/{id}', [App\Http\Controllers\RazonSocialController::class, 'admin_edit'])->name('admin.razones-sociales.edit');
        Route::post('/razones-sociales/update/{id}', [App\Http\Controllers\RazonSocialController::class, 'admin_update'])->name('admin.razones-sociales.update');

        Route::get('/gestiones/index', [App\Http\Controllers\GestionController::class, 'admin_index'])->name('admin.gestiones.index');
        Route::get('/gestiones/create', [App\Http\Controllers\GestionController::class, 'admin_create'])->name('admin.gestiones.create');
        Route::post('/gestiones/store', [App\Http\Controllers\GestionController::class, 'admin_store'])->name('admin.gestiones.store');
        Route::get('/gestiones/show/{id}', [App\Http\Controllers\GestionController::class, 'admin_show'])->name('admin.gestiones.show');
        Route::get('/gestiones/edit/{id}', [App\Http\Controllers\GestionController::class, 'admin_edit'])->name('admin.gestiones.edit');
        Route::post('/gestiones/update/{id}', [App\Http\Controllers\GestionController::class, 'admin_update'])->name('admin.gestiones.update');

        Route::get('/reportes/index', [App\Http\Controllers\ReporteController::class, 'admin_index'])->name('admin.reportes.index');
        Route::get('/reportes/create', [App\Http\Controllers\ReporteController::class, 'admin_create'])->name('admin.reportes.create');
        Route::post('/reportes/store', [App\Http\Controllers\ReporteController::class, 'admin_store'])->name('admin.reportes.store');
        Route::get('/reportes/show/{id}', [App\Http\Controllers\ReporteController::class, 'admin_show'])->name('admin.reportes.show');
        Route::get('/reportes/edit/{id}', [App\Http\Controllers\ReporteController::class, 'admin_edit'])->name('admin.reportes.edit');
        Route::post('/reportes/update/{id}', [App\Http\Controllers\ReporteController::class, 'admin_update'])->name('admin.reportes.update');
    });

    // CLIENTE
    Route::group(['prefix' => 'cliente', 'middleware' => ['cliente']], function() {
        Route::get('/empresas/index', [App\Http\Controllers\EmpresaController::class, 'cliente_index'])->name('cliente.empresas.index');
        Route::get('/empresas/create', [App\Http\Controllers\EmpresaController::class, 'cliente_create'])->name('cliente.empresas.create');
        Route::post('/empresas/store', [App\Http\Controllers\EmpresaController::class, 'cliente_store'])->name('cliente.empresas.store');
        Route::get('/empresas/show/{id}', [App\Http\Controllers\EmpresaController::class, 'cliente_show'])->name('cliente.empresas.show');
        Route::get('/empresas/edit/{id}', [App\Http\Controllers\EmpresaController::class, 'cliente_edit'])->name('cliente.empresas.edit');
        Route::post('/empresas/update/{id}', [App\Http\Controllers\EmpresaController::class, 'cliente_update'])->name('cliente.empresas.update');

        Route::get('/usuarios/index', [App\Http\Controllers\UserController::class, 'cliente_index'])->name('cliente.usuarios.index');
        Route::get('/usuarios/create', [App\Http\Controllers\UserController::class, 'cliente_create'])->name('cliente.usuarios.create');
        Route::post('/usuarios/store', [App\Http\Controllers\UserController::class, 'cliente_store'])->name('cliente.usuarios.store');
        Route::get('/usuarios/show/{id}', [App\Http\Controllers\UserController::class, 'cliente_show'])->name('cliente.usuarios.show');
        Route::get('/usuarios/edit/{id}', [App\Http\Controllers\UserController::class, 'cliente_edit'])->name('cliente.usuarios.edit');
        Route::post('/usuarios/update/{id}', [App\Http\Controllers\UserController::class, 'cliente_update'])->name('cliente.usuarios.update');

        Route::get('/razones-sociales/index', [App\Http\Controllers\RazonSocialController::class, 'cliente_index'])->name('cliente.razones-sociales.index');
        Route::get('/razones-sociales/create', [App\Http\Controllers\RazonSocialController::class, 'cliente_create'])->name('cliente.razones-sociales.create');
        Route::post('/razones-sociales/store', [App\Http\Controllers\RazonSocialController::class, 'cliente_store'])->name('cliente.razones-sociales.store');
        Route::get('/razones-sociales/show/{id}', [App\Http\Controllers\RazonSocialController::class, 'cliente_show'])->name('cliente.razones-sociales.show');
        Route::get('/razones-sociales/edit/{id}', [App\Http\Controllers\RazonSocialController::class, 'cliente_edit'])->name('cliente.razones-sociales.edit');
        Route::post('/razones-sociales/update/{id}', [App\Http\Controllers\RazonSocialController::class, 'cliente_update'])->name('cliente.razones-sociales.update');

        Route::get('/gestiones/index', [App\Http\Controllers\GestionController::class, 'cliente_index'])->name('cliente.gestiones.index');
        Route::get('/gestiones/create', [App\Http\Controllers\GestionController::class, 'cliente_create'])->name('cliente.gestiones.create');
        Route::post('/gestiones/store', [App\Http\Controllers\GestionController::class, 'cliente_store'])->name('cliente.gestiones.store');
        Route::get('/gestiones/show/{id}', [App\Http\Controllers\GestionController::class, 'cliente_show'])->name('cliente.gestiones.show');
        Route::get('/gestiones/edit/{id}', [App\Http\Controllers\GestionController::class, 'cliente_edit'])->name('cliente.gestiones.edit');
        Route::post('/gestiones/update/{id}', [App\Http\Controllers\GestionController::class, 'cliente_update'])->name('cliente.gestiones.update');

        Route::get('/reportes/index', [App\Http\Controllers\ReporteController::class, 'cliente_index'])->name('cliente.reportes.index');
        Route::get('/reportes/create', [App\Http\Controllers\ReporteController::class, 'cliente_create'])->name('cliente.reportes.create');
        Route::post('/reportes/store', [App\Http\Controllers\ReporteController::class, 'cliente_store'])->name('cliente.reportes.store');
        Route::get('/reportes/show/{id}', [App\Http\Controllers\ReporteController::class, 'cliente_show'])->name('cliente.reportes.show');
        Route::get('/reportes/edit/{id}', [App\Http\Controllers\ReporteController::class, 'cliente_edit'])->name('cliente.reportes.edit');
        Route::post('/reportes/update/{id}', [App\Http\Controllers\ReporteController::class, 'cliente_update'])->name('cliente.reportes.update');

        Route::get('/facturas/index', [App\Http\Controllers\FacturaController::class, 'cliente_index'])->name('cliente.facturas.index');
        Route::get('/facturas/create', [App\Http\Controllers\FacturaController::class, 'cliente_create'])->name('cliente.facturas.create');
        Route::post('/facturas/store', [App\Http\Controllers\FacturaController::class, 'cliente_store'])->name('cliente.facturas.store');
        Route::get('/facturas/show/{id}', [App\Http\Controllers\FacturaController::class, 'cliente_show'])->name('cliente.facturas.show');
        Route::get('/facturas/edit/{id}', [App\Http\Controllers\FacturaController::class, 'cliente_edit'])->name('cliente.facturas.edit');
        Route::post('/facturas/update/{id}', [App\Http\Controllers\FacturaController::class, 'cliente_update'])->name('cliente.facturas.update');
    });

    // CONSULTOR
    Route::group(['prefix' => 'consultor', 'middleware' => ['consultor']], function() {
        Route::get('/empresas/index', [App\Http\Controllers\EmpresaController::class, 'consultor_index'])->name('consultor.empresas.index');
        Route::get('/empresas/create', [App\Http\Controllers\EmpresaController::class, 'consultor_create'])->name('consultor.empresas.create');
        Route::post('/empresas/store', [App\Http\Controllers\EmpresaController::class, 'consultor_store'])->name('consultor.empresas.store');
        Route::get('/empresas/show/{id}', [App\Http\Controllers\EmpresaController::class, 'consultor_show'])->name('consultor.empresas.show');
        Route::get('/empresas/edit/{id}', [App\Http\Controllers\EmpresaController::class, 'consultor_edit'])->name('consultor.empresas.edit');
        Route::post('/empresas/update/{id}', [App\Http\Controllers\EmpresaController::class, 'consultor_update'])->name('consultor.empresas.update');

        Route::get('/usuarios/index', [App\Http\Controllers\UserController::class, 'consultor_index'])->name('consultor.usuarios.index');
        Route::get('/usuarios/create', [App\Http\Controllers\UserController::class, 'consultor_create'])->name('consultor.usuarios.create');
        Route::post('/usuarios/store', [App\Http\Controllers\UserController::class, 'consultor_store'])->name('consultor.usuarios.store');
        Route::get('/usuarios/show/{id}', [App\Http\Controllers\UserController::class, 'consultor_show'])->name('consultor.usuarios.show');
        Route::get('/usuarios/edit/{id}', [App\Http\Controllers\UserController::class, 'consultor_edit'])->name('consultor.usuarios.edit');
        Route::post('/usuarios/update/{id}', [App\Http\Controllers\UserController::class, 'consultor_update'])->name('consultor.usuarios.update');

        Route::get('/razones-sociales/index', [App\Http\Controllers\RazonSocialController::class, 'consultor_index'])->name('consultor.razones-sociales.index');
        Route::get('/razones-sociales/create', [App\Http\Controllers\RazonSocialController::class, 'consultor_create'])->name('consultor.razones-sociales.create');
        Route::post('/razones-sociales/store', [App\Http\Controllers\RazonSocialController::class, 'consultor_store'])->name('consultor.razones-sociales.store');
        Route::get('/razones-sociales/show/{id}', [App\Http\Controllers\RazonSocialController::class, 'consultor_show'])->name('consultor.razones-sociales.show');
        Route::get('/razones-sociales/edit/{id}', [App\Http\Controllers\RazonSocialController::class, 'consultor_edit'])->name('consultor.razones-sociales.edit');
        Route::post('/razones-sociales/update/{id}', [App\Http\Controllers\RazonSocialController::class, 'consultor_update'])->name('consultor.razones-sociales.update');

        Route::get('/gestiones/index', [App\Http\Controllers\GestionController::class, 'consultor_index'])->name('consultor.gestiones.index');
        Route::get('/gestiones/create', [App\Http\Controllers\GestionController::class, 'consultor_create'])->name('consultor.gestiones.create');
        Route::post('/gestiones/store', [App\Http\Controllers\GestionController::class, 'consultor_store'])->name('consultor.gestiones.store');
        Route::get('/gestiones/show/{id}', [App\Http\Controllers\GestionController::class, 'consultor_show'])->name('consultor.gestiones.show');
        Route::get('/gestiones/edit/{id}', [App\Http\Controllers\GestionController::class, 'consultor_edit'])->name('consultor.gestiones.edit');
        Route::post('/gestiones/update/{id}', [App\Http\Controllers\GestionController::class, 'consultor_update'])->name('consultor.gestiones.update');

        Route::get('/reportes/index', [App\Http\Controllers\ReporteController::class, 'consultor_index'])->name('consultor.reportes.index');
        Route::get('/reportes/create', [App\Http\Controllers\ReporteController::class, 'consultor_create'])->name('consultor.reportes.create');
        Route::post('/reportes/store', [App\Http\Controllers\ReporteController::class, 'consultor_store'])->name('consultor.reportes.store');
        Route::get('/reportes/show/{id}', [App\Http\Controllers\ReporteController::class, 'consultor_show'])->name('consultor.reportes.show');
        Route::get('/reportes/edit/{id}', [App\Http\Controllers\ReporteController::class, 'consultor_edit'])->name('consultor.reportes.edit');
        Route::post('/reportes/update/{id}', [App\Http\Controllers\ReporteController::class, 'consultor_update'])->name('consultor.reportes.update');

        Route::get('/facturas/index', [App\Http\Controllers\FacturaController::class, 'consultor_index'])->name('consultor.facturas.index');
        Route::get('/facturas/create', [App\Http\Controllers\FacturaController::class, 'consultor_create'])->name('consultor.facturas.create');
        Route::post('/facturas/store', [App\Http\Controllers\FacturaController::class, 'consultor_store'])->name('consultor.facturas.store');
        Route::get('/facturas/show/{id}', [App\Http\Controllers\FacturaController::class, 'consultor_show'])->name('consultor.facturas.show');
        Route::get('/facturas/edit/{id}', [App\Http\Controllers\FacturaController::class, 'consultor_edit'])->name('consultor.facturas.edit');
        Route::post('/facturas/update/{id}', [App\Http\Controllers\FacturaController::class, 'consultor_update'])->name('consultor.facturas.update');
    });
    


});
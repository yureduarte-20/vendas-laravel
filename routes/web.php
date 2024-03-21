<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(\App\Http\Controllers\ClienteController::class)
        ->group(function (){
        Route::post('/clientes', 'store')->name('clientes.store');
        Route::get('/clientes', 'index')->name('clientes.index') ;
        Route::get('/clientes/create', 'create')->name('clientes.create');
        Route::get('/clientes/{cliente}/edit', 'edit')->name('clientes.edit');
        Route::put('/clientes/{cliente}', 'update')->name('clientes.update');
        Route::delete('/clientes/{cliente}', 'destroy')->name('clientes.destroy');
    });
    Route::get('/clientes/find', [\App\Http\Controllers\SearchController::class, 'find_cliente'])->name('clientes.find');
    Route::get('/produtos/find', [\App\Http\Controllers\SearchController::class, 'find_produtos'])->name('produtos.find');
    Route::resource('produtos', \App\Http\Controllers\ProdutoController::class)->except(['show']);
    Route::resource('vendas', \App\Http\Controllers\VendaController::class)->except(['show']);
    Route::get('/relatorio-vendas', \App\Http\Controllers\ReportsController::class)->name('generate.pdf');
    Route::get('/relatorio-vendas', [\App\Http\Controllers\ReportsController::class, 'report'])->name('generate.pdf');
});


require __DIR__.'/auth.php';

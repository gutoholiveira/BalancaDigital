<?php

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\ProdutosController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return view('dashboard');
})->name('dashboard');

/* Rotas para usuarios */
Route::group(['prefix' => 'usuarios'], function () {
    Route::get('', [UsersController::class, 'index'])->name('usuarios')->middleware('auth');
    Route::post('/store', [UsersController::class, 'store'])->name('usuario.store')->middleware('auth');
    Route::put('/{id}/update', [UsersController::class, 'update'])->name('usuario.update')->middleware('auth');
    Route::delete('/{id}/delete', [UsersController::class, 'destroy'])->name('usuario.destroy')->middleware('auth');
});

/* Rotas para produtos */
Route::group(['prefix' => 'produtos'], function () {
    Route::get('', [ProdutosController::class, 'index'])->name('produtos')->middleware('auth');
    Route::post('/store', [ProdutosController::class, 'store'])->name('produto.store')->middleware('auth');
    Route::put('/{id}/update', [ProdutosController::class, 'update'])->name('produto.update')->middleware('auth');
    Route::delete('/{id}/delete', [ProdutosController::class, 'destroy'])->name('produto.destroy')->middleware('auth');
    Route::get('/getbycodigo', [ProdutosController::class, 'getCodigo'])->name('produto.getCodigo')->middleware('auth');
    Route::get('/getbydescricao', [ProdutosController::class, 'getDescricao'])->name('produto.getDescricao')->middleware('auth');
    Route::get('/getbyid', [ProdutosController::class, 'getId'])->name('produto.getId')->middleware('auth');
});

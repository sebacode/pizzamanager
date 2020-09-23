<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('auth.login'); });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/message', [App\Http\Controllers\MessageController::class, 'index'])->name('message');

Route::get('/pizza/new', [App\Http\Controllers\PizzaController::class, 'loadNew'])->name('pizza.new');
Route::get('/pizza/{id}/edit', [App\Http\Controllers\PizzaController::class, 'loadDetail'])->name('pizza.edit');
Route::get('/pizza/list', [App\Http\Controllers\PizzaController::class, 'loadList'])->name('pizza.list');
Route::post('/pizza', [App\Http\Controllers\PizzaController::class, 'save'])->name('pizza.save');
Route::delete('/pizza', [App\Http\Controllers\PizzaController::class, 'delete'])->name('pizza.delete');

Route::get('/ingredient/new', [App\Http\Controllers\IngredientController::class, 'loadNew'])->name('ingredient.new');
Route::get('/ingredient/{id}/edit', [App\Http\Controllers\IngredientController::class, 'loadDetail'])->name('ingredient.edit');
Route::get('/ingredient/list', [App\Http\Controllers\IngredientController::class, 'loadList'])->name('ingredient.list');
Route::post('/ingredient', [App\Http\Controllers\IngredientController::class, 'save'])->name('ingredient.save');
Route::delete('/ingredient', [App\Http\Controllers\IngredientController::class, 'delete'])->name('ingredient.delete');
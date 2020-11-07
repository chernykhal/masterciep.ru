<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\RecipeController;
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
Route::put('products/store', [ProductController::class, 'store'])->name('products.store');
Route::resource('products', ProductController::class)->except('store');

Route::put('recipes/store', [RecipeController::class, 'store'])->name('recipes.store');
Route::resource('recipes', RecipeController::class)->except('store');

Route::put('types/store', [ProductTypeController::class, 'store'])->name('types.store');
Route::resource('types', ProductTypeController::class)->except(['store']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

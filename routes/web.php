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

Route::put('products/store', [ProductController::class, 'store'])->name('products.store')->middleware('auth');
Route::get('my/products', [ProductController::class, 'usersProductsIndex'])->name('my.products')->middleware('auth');
Route::post('my/products/{product}', [ProductController::class, 'usersProductsUpdate'])->name('my.products.update')->middleware('auth');
Route::post('my/products/{product}/destroy', [ProductController::class, 'usersProductsDestroy'])->name('my.products.destroy')->middleware('auth');
Route::post('products/{product}/add', [ProductController::class, 'add'])->name('products.add')->middleware('auth');
Route::get('scan',[ProductController::class,'scan'])->name('products.scan')->middleware('auth');
Route::get('scan-confirmation',[ProductController::class,'getProductsFromQr'])->name('products.scan.getProductsFromQr')->middleware('auth');
Route::post('scan-add',[ProductController::class,'addProductsFromQr'])->name('products.scan.addProductsFromQr')->middleware('auth');
Route::resource('products', ProductController::class)->except('store')->middleware('auth');

Route::put('recipes/store', [RecipeController::class, 'store'])->name('recipes.store')->middleware('auth');
Route::get('my/recipes/', [RecipeController::class, 'usersRecipesIndex'])->name('my.recipes')->middleware('auth');
Route::get('my/recipes/{recipe}/', [RecipeController::class, 'cook'])->name('my.recipes.cook')->middleware('auth');
Route::resource('recipes', RecipeController::class)->except('store')->middleware('auth');

Route::put('types/store', [ProductTypeController::class, 'store'])->name('types.store')->middleware('auth');
Route::resource('types', ProductTypeController::class)->except(['store'])->middleware('auth');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
Route::redirect('/','/dashboard');

<?php

use App\Http\Controllers\ProductController;
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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', [ProductController::class, 'index'])->name('productList');
    Route::get('/product/{id?}', [ProductController::class, 'productForm'])->name('productForm'); // new / update route. Differentiate by ID
    Route::post('/product/{id?}', [ProductController::class, 'productStore'])->name('productStore'); // new / update route. Differentiate by ID
    Route::delete('/product/{id}', [ProductController::class, 'productDestroy'])->name('productDestroy');
});

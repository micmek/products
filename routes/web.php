<?php

use App\Http\Controllers\ProductsController;
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

Route::get('/product-list', [ProductsController::class, 'list']);


Route::get('/product-list-data', [ProductsController::class, 'getListData']);
Route::post('/product-save', [ProductsController::class, 'saveProduct']);
Route::get('/product-get-data/{id}', [ProductsController::class, 'getProductData']);

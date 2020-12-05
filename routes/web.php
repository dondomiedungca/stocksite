<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\ProductsController;

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

// VIEWS

Route::get('/admin/signin', function () {
    return view('admin.signin');
})->name('signin');

Route::group(['middleware' => 'adminRoute'], function () {
    Route::prefix('admin/dashboard')->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard.index');
        });
    });

    Route::prefix('admin/purchasing')->group(function () {
        Route::get('/', [PurchasingController::class, 'index']);
    });

    Route::prefix('admin/products')->group(function () {
        Route::get('/', [ProductsController::class, 'index']);
        Route::get('/add-product-type', [ProductsController::class, 'productTypes']);
    });

    Route::post('/admin/logout', function() {
        Auth::logout();
        return redirect('/admin/signin');
    })->name('admin.logout');

});

// APIS

Route::prefix('admin')->group(function () {
    Route::post('/signin', [LoginController::class, 'authenticate']);
});

Route::group(['middleware' => 'adminRoute'], function () {

    Route::prefix('admin/products')->group(function () {
        Route::post('/add-product-type', [ProductsController::class, 'addProductTypes']);
        Route::get('/get-product-types', [ProductsController::class, 'getProductTypes']);
        Route::get('/remove-product-type/{id}', [ProductsController::class, 'removeProductTypes']);
    });

});
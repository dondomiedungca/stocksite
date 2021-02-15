<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\AddressTypesController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ReceiverController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransactionsController;

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
    if(!Auth::check()) {
        return view('admin.signin');
    } else {
        return redirect('/admin/dashboard');
    }
})->name('signin');

Route::group(['middleware' => 'adminRoute'], function () {

    Route::prefix('admin')->group(function () {
        Route::fallback(function() {
            return view('admin.index');
        });
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
        Route::get('/get-all-product-types', [ProductsController::class, 'getAllProductTypes']);
        Route::get('/remove-product-type/{id}', [ProductsController::class, 'removeProductTypes']);
        Route::get('/get-stock-number', [ProductsController::class, 'getStockNumber']);
        Route::get('/get-cosmetics', [ProductsController::class, 'getCosmetics']);
        Route::get('/get-item-statuses', [ProductsController::class, 'getItemStatuses']);
        Route::post('/save-manual-item', [ProductsController::class, 'saveManualItem']);
        Route::post('/save-file', [ProductsController::class, 'saveFile']);
        Route::post('/update-product', [ProductsController::class, 'updateProduct']);
        Route::post('/remove-product', [ProductsController::class, 'removeProduct']);
        Route::post('/get-products-via-product-types', [ProductsController::class, 'getProductsViaProductType']);
    });

    Route::prefix('admin/supplier')->group(function () {
        Route::post('/add-supplier', [SuppliersController::class, 'addSupplier']);
        Route::get('/get-suppliers', [SuppliersController::class, 'getSuppliers']);
        Route::post('/get-paginate-suppliers', [SuppliersController::class, 'getPaginateSuppliers']);
        Route::post('/update-supplier', [SuppliersController::class, 'updateSupplier']);
        Route::post('/remove-supplier', [SuppliersController::class, 'removeSupplier']);
    });

    Route::prefix('admin/address')->group(function () {
        Route::get('/get-address-type', [AddressTypesController::class, 'getAddressTypes']);
    });

    Route::prefix('admin/countries')->group(function () {
        Route::get('/get-countries', [SuppliersController::class, 'getCountries']);
    });

    Route::prefix('admin/currency')->group(function () {
        Route::get('/get-currency', [CurrencyController::class, 'getCurrency']);
    });

    Route::prefix('admin/delivery')->group(function () {
        Route::get('/get-delivery-statuses', [TransactionsController::class, 'getDeliveries']);
    });

    Route::prefix('admin/item')->group(function () {
        Route::get('/get-item-statuses', [TransactionsController::class, 'getItemStatuses']);
    });

    Route::prefix('admin/manufacturer')->group(function () {
        Route::get('/get-manufacturer-types', [ManufacturerController::class, 'getManufacturerTypes']);
    });

    Route::prefix('admin/receiver')->group(function () {
        Route::post('/add-receiver', [ReceiverController::class, 'addReceiver']);
        Route::get('/get-receivers', [ReceiverController::class, 'getReceivers']);
        Route::post('/get-paginate-receivers', [ReceiverController::class, 'getReceiverPaginate']);
        Route::post('/update-receiver', [ReceiverController::class, 'updateReceiver']);
        Route::post('/remove-receiver', [ReceiverController::class, 'removeReceiver']);
    });

    Route::prefix('admin/payment')->group(function () {
        Route::get('/get-payment-statuses', [TransactionsController::class, 'getPayments']);
    });

    Route::prefix('admin/purchasing')->group(function () {
        Route::post('/create', [PurchasingController::class, 'store']);
        Route::get('/purchasing-all-list', [PurchasingController::class, 'getAllPurchaseOrders']);
        Route::get('/purchasing-get-details/{id}', [PurchasingController::class, 'getPurchaseOrder']);
        Route::get('/get-purchasing-type/{id}', [PurchasingController::class, 'getPurchasingType']);
        Route::get('/remove-purchasing/{id}', [PurchasingController::class, 'removePurchasing']);
    });

    Route::prefix('admin/reports')->group(function () {
        Route::get('/get-queue-batches', [ReportsController::class, 'getBatches']);
        Route::get('/get-queue-batches-failed', [ReportsController::class, 'getBatchesFailed']);
        Route::get('/get-queue-batches-completed', [ReportsController::class, 'getBatchesCompleted']);
        Route::get('/get-current-queue-processing', [ReportsController::class, 'getCurrent']);
        Route::get('/queue-retry/{id}', [ReportsController::class, 'queueRetry']);
    });

    Route::prefix('admin/transactions')->group(function () {
        Route::get('/get-transaction-statuses', [TransactionsController::class, 'getTransactionStatuses']);
    });

});
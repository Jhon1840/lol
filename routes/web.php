<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//Route::get('/productos/subir-csv', [App\Http\Controllers\CsvController::class, 'upload'])->name('productos.upload.csv');

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/upload-csv', 'CsvController@upload');
Route::resource('/product',ProductController::class);
//Route::resource('/product', App\Http\Controllers\ProductController::class);
Route::get('/ppp', [\App\Http\Controllers\HomeController::class, 'ppp'])->name('ppp');

//Route::get('users/export/', [UsersController::class, 'export']);
Route::post('/upload-csv', 'UploadController@uploadCsv');
Route::get('/export/products', [ExportController::class, 'exportProducts'])->name('export.products');
Route::get('/producto/exportar-datos-txt', 'ProductoController@exportarDatosTxt')->name('producto.exportar-datos-txt');

Route::post('/import/products', [ImportController::class, 'importProducts'])->name('import.products');


//ventas
Route::get('/ventas', [\App\Http\Controllers\HomeController::class, 'venta'])->name('venta');
Route::resource('/ventas', App\Http\Controllers\VentaController::class);

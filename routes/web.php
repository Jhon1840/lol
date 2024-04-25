<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;

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
//productos
Route::resource('/product',ProductController::class);




//Route::resource('/product', App\Http\Controllers\ProductController::class);
Route::get('/ppp', [\App\Http\Controllers\HomeController::class, 'ppp'])->name('ppp');

//Route::get('users/export/', [UsersController::class, 'export']);
Route::post('/upload-csv', 'UploadController@uploadCsv');
Route::get('/export/products', [ExportController::class, 'exportProducts'])->name('export.products');
Route::get('/producto/exportar-datos-txt', 'ProductoController@exportarDatosTxt')->name('producto.exportar-datos-txt');

Route::post('/import/products', [ImportController::class, 'importProducts'])->name('import.products');


//Usuarios
//Route::get('/usuarios', [\App\Http\Controllers\HomeController::class, 'usuarios'])->name('usuarios');
Route::resource('/usuarios',UserController::class);



//vneta
Route::post('/realizar-venta', [VentaController::class, 'store'])->name('realizar-venta');


//ventas
Route::get('/ventas', [\App\Http\Controllers\HomeController::class, 'venta'])->name('venta');

Route::resource('/ventas', App\Http\Controllers\VentaController::class);


// Ruta para manejar la solicitud AJAX
Route::post('/ventas/proceedPago', 'VentaController@proceedPago')->name('ventas.proceedPago');


//factura
Route::post('/generar-factura', 'VentaController@generarFactura')->name('generar.factura');

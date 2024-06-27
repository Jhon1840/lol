<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\Descuentos;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CajaController;

use App\Http\Controllers\DescuentoController;

Route::get('/', function () {
    return view('welcome');
});
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

// Cancelar venta
Route::post('/cancelar-venta', [VentaController::class, 'cancelar'])->name('cancelar-venta');

//cancelarventa(devolver producto)
Route::post('/cancelarventaproducto/{id}', [VentaController::class, 'cancelarVenta'])->name('cancelar-venta-producto');

//ventas
Route::get('/ventas', [\App\Http\Controllers\HomeController::class, 'venta'])->name('venta');

Route::resource('/ventas', App\Http\Controllers\VentaController::class);

//Metricas
Route::get('/metricas', [\App\Http\Controllers\Metricas::class, 'index'])->name('metricas');

//metricas
Route::get('/productos-mas-vendidos/{period}', [\App\Http\Controllers\Metricas::class, 'getMostSoldProducts']);

// Ruta para manejar la solicitud AJAX
Route::post('/ventas/proceedPago', 'VentaController@proceedPago')->name('ventas.proceedPago');


//factura
Route::post('/generar-factura', 'VentaController@generarFactura')->name('generar.factura');


Route::get('invoices/{filename}', 'InvoiceController@download')->name('invoices.download');

Route::get('/test-file', function () {
    \Illuminate\Support\Facades\Storage::disk('public')->put('test.txt', 'Hello, world!');
    return 'Archivo creado con éxito';
});



//caja
Route::post('/ventas/toggleCaja', [VentaController::class, 'toggleCaja'])->name('ventas.toggleCaja');

//cerrar caja
Route::post('/ventas/cerrarCaja', [VentaController::class, 'cerrarCaja'])->name('ventas.cerrarCaja');


Route::post('/ventas/caja/{id}', [VentaController::class, 'getDineroEnCaja']);


Route::post('/ventas/realizar', [VentaController::class, 'realizarVenta'])->name('realizar');


//Descuentos
Route::resource('/descuentos', App\Http\Controllers\DescuentoController::class);

Route::get('/facturas', [\App\Http\Controllers\Facturas::class, 'index'])->name('facturas');

Route::get('/caja', [CajaController::class, 'index'])->name('caja');

Route::post('/user', [UserController::class, 'store'])->name('user.store');

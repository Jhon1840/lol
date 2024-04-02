<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CsvController;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/productos/subir-csv', [App\Http\Controllers\CsvController::class, 'upload'])->name('productos.upload.csv');

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/upload-csv', 'CsvController@upload');
Route::resource('/product',ProductController::class);
//Route::resource('/product', App\Http\Controllers\ProductController::class);

Route::post('/upload-csv', 'UploadController@uploadCsv');

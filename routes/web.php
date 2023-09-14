<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',function (){
    return view('auth.login');
});
Auth::routes(['register'=>false]);

Route::middleware('admin')->group(function (){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//    Route::get('/{page}', [AdminController::class,'index']);
    //

    Route::resource('invoices',InvoiceController::class);
    Route::resource('sections',SectionController::class);
    Route::resource('products',ProductController::class);

    Route::get('section/{id}',[InvoiceController::class,'getProducts']);
});


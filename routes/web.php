<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
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

Route::middleware(['admin','checkPermission'])->group(function (){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//    Route::get('/{page}', [AdminController::class,'index']);
    //
    Route::resource('invoices',InvoiceController::class);
    Route::resource('sections',SectionController::class);
    Route::resource('products',ProductController::class);
    Route::resource('invoiceDetails',InvoicesDetailsController::class);
    Route::get('details/{id}',[InvoicesDetailsController::class,'showDetails'])->name('showDetails');
    Route::Post('delete_attachment',[InvoicesDetailsController::class,'deleteFile'])->name('deleteFile');
    Route::get('download/{invoice_number}/{file_name}',[InvoicesDetailsController::class,'download'])->name('download_file');
    Route::get('section/{id}',[InvoiceController::class,'getProducts']);
    Route::Post('archive',[InvoiceController::class,'archive'])->name('archiveInvoice');
    Route::Post('delete',[InvoiceController::class,'delete'])->name('deleteInvoice');
    Route::get('show_status/{id}',[InvoiceController::class,'showStatus'])->name('showStatus');
    Route::Post('update_status',[InvoiceController::class,'updateStatus'])->name('updateStatus');

    Route::get('invoice_paid',[InvoiceController::class,'invoicePaid'])->name('invoicePaid');
    Route::get('invoice_unpaid',[InvoiceController::class,'invoiceUnpaid'])->name('invoiceUnpaid');
    Route::get('invoice_partially',[InvoiceController::class,'invoicePartiallyPaid'])->name('invoicePartiallyPaid');
    Route::get('invoice_archive',[InvoiceController::class,'invoiceArchive'])->name('invoiceArchive');

    Route::get('restore_invoice/{id}',[InvoiceController::class,'restoreInvoice'])->name('restoreInvoice');
    Route::get('print_invoice/{id}',[InvoiceController::class,'printInvoice'])->name('printInvoice');
    Route::get('export', [InvoiceController::class,'export'])->name('exportInvoices');

        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::post('delete_user',[UserController::class,'delete'])->name('deleteUser');





});


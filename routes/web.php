<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceiptController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/print-receipt', [ReceiptController::class, 'printReceipt']);
Route::get('/print-struk', [ReceiptController::class, 'printStruk']);
<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceiptController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('fullscreen/{id}', [ReceiptController::class, 'showFullscreen'])->name('receipts.fullscreen');
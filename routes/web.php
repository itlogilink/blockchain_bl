<?php

use App\Http\Controllers\BillOfLadingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('bl.index');
});

Route::prefix('bl')->name('bl.')->group(function () {
    Route::get('/', [BillOfLadingController::class, 'index'])->name('index');
    Route::get('/create', [BillOfLadingController::class, 'create'])->name('create');
    Route::post('/', [BillOfLadingController::class, 'store'])->name('store');
    Route::get('/chain', [BillOfLadingController::class, 'chain'])->name('chain');
    Route::get('/{billOfLading}', [BillOfLadingController::class, 'show'])->name('show');
    Route::get('/{billOfLading}/transfer', [BillOfLadingController::class, 'transferForm'])->name('transfer.form');
    Route::post('/{billOfLading}/transfer', [BillOfLadingController::class, 'transfer'])->name('transfer');
});

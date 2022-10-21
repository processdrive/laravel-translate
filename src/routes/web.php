<?php

use Illuminate\Support\Facades\Route;
use ProcessDrive\LaravelCloudTranslation\Controllers\TransController;

Route::get('translation/index', [TransController::class, 'index'])->name('translation.index');
Route::get('translation/get-data', [TransController::class, 'getTranslation'])->name('translation.getdata');
Route::post('translation/get-data', [TransController::class, 'getTranslation'])->name('translation.getdata');
Route::post('translation/store', [TransController::class, 'store'])->name('translation.store');
Route::post('translation/update', [TransController::class, 'update'])->name('translation.update');
Route::post('translation/delete', [TransController::class, 'destory'])->name('translation.delete');
Route::post('translation/new-language', [TransController::class, 'storeNewLanguage'])->name('translation.newlanguage');
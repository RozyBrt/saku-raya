<?php

use Illuminate\Http\request;
use Illuminate\Support\Facades\Route;;

// Import dyahhh
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\TopUpController;

Route::get('/user', function (Request $request){
    return $request->user();
})->middleware('auth:sanctum');

// Panggil dyahh
Route::get('/check-status', [StatusController::class, 'check']);
Route::post('/transfer', [TransferController::class, 'store']);
Route::post('/top-up', [TopUpController::class, 'store']);
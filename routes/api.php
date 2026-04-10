<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\TopUpController;
use App\Http\Controllers\Api\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Jalur Umum: Siapa aja bisa akses buat dapet Kunci (Token)
Route::post('/login', [AuthController::class, 'login']);

// Jalur VIP: Cuma yang bawa Kunci (Token) yang boleh lewat
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/check-status', [StatusController::class, 'check']);
    Route::post('/transfer', [TransferController::class, 'store']);
    Route::post('/top-up', [TopUpController::class, 'store']);
});
<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('accounts', AccountController::class);
Route::get('testeRota', [AccountController::class, 'testeSeApareceARota']);

Route::prefix('accounts')->group(function () {
    Route::apiResource('/', AccountController::class)->parameters([
        '' => 'account'
    ])->shallow(); 

    Route::get('/getBalance', [AccountController::class, 'testeSeApareceARota']);
});
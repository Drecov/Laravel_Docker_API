<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/balance', [AccountController::class, 'getBalance']);
Route::get('/allAccounts', [AccountController::class, 'getAllAccounts']);
Route::post('/event', [AccountController::class, 'processEvent']);
Route::post('/reset', [AccountController::class, 'resetAccount']);

Route::fallback(function() {
    // abort(400, 'Bad Request. See endpoint List.');
    return response()->json([
        'message' => 'Rota web não encontrada. Verifique a URL ou consulte a documentação da API.',
        'status' => 404
    ], 404);
});
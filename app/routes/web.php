<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/balance', [AccountController::class, 'getBalance']);
Route::post('/event', [AccountController::class, 'testeSeApareceARota']);
Route::post('/reset', [AccountController::class, 'testeSeApareceARota']);

Route::fallback(function() {
    // abort(400, 'Bad Request. See endpoint List.');
    return response()->json([
        'message' => 'Rota web não encontrada. Verifique a URL ou consulte a documentação da API.',
        'status' => 404
    ], 404);
});
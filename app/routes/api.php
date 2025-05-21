<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::apiResource('accounts', AccountController::class);
// Route::get('testeRota', [AccountController::class, 'testeSeApareceARota']);

// Route::prefix('accounts')->group(function () {
//     Route::apiResource('/', AccountController::class)->parameters([
//         '' => 'account'
//     ])->shallow(); 

//     Route::get('/getBalance', [AccountController::class, 'testeSeApareceARota']);
// });

Route::fallback(function() {
    // abort(400, 'Bad Request. See endpoint List.');
    return response()->json([
        'message' => 'Rota de API não encontrada. Verifique a URL ou consulte a documentação da API.',
        'status' => 404
    ], 404);
});
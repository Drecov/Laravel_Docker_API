<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::fallback(function() {
    // abort(400, 'Bad Request. See endpoint List.');
    return response()->json([
        'message' => 'Rota de API não encontrada. Verifique a URL ou consulte a documentação da API.',
        'status' => 404
    ], 404);
});
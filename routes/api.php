<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('generate-token', [ApiController::class, 'generateToken']);
Route::post('refresh-token', [ApiController::class, 'refreshToken']);
Route::post('get-token', [ApiController::class, 'getToken']);
Route::post('create-account', [ApiController::class, 'createAccount']);
Route::post('create-deal', [ApiController::class, 'createDeal']);

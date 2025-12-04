<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\PresentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/presents', [PresentController::class, 'index']);
Route::get('/presents-type-ts', [PresentController::class, 'index2']);
Route::get('/presents-all-ts', [PresentController::class, 'index3']);

Route::post('/new-present', [PresentController::class, 'store']);
<?php

use App\Http\Controllers\ChildController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\PresentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//lekérdezi az összes ajándék típust az ajándékokkal együtt
Route::get('/presents', [PresentController::class, 'index']);
//lekérdezi az összes ajándék típust az ajándékokkal együtt, az ajándék típusok created_at és updated_at mezői láthatóak
Route::get('/presents-type-ts', [PresentController::class, 'index2']);
//lekérdezi az összes ajándék típust az ajándékokkal együtt, az ajándék típusok created_at és updated_at mezői, valamint az ajándékok created_at mezői láthatóak
Route::get('/presents-all-ts', [PresentController::class, 'index3']);

//új ajándék létrehozása
Route::post('/new-present', [PresentController::class, 'store']);

//gyerekek lekérdezése
Route::get('/children', [ChildController::class, 'index']);
//új gyerek létrehozása
Route::post('/new-child', [ChildController::class, 'store']);
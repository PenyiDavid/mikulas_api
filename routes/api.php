<?php

use App\Http\Controllers\ChildController;
use App\Http\Controllers\ChildPresentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\PresentController;
use App\Http\Controllers\PresentTypeController;

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

//ajándék típusok lekérdezése
Route::get('/present-types', [PresentTypeController::class, 'index']);
//új ajándék típus létrehozása
Route::post('/new-present-type', [PresentTypeController::class, 'store']);


//pivot controller -> php artisan make:controller ChildPresentController --api
//angol kommentek, függvények nevei generáltak
Route::get('/wishes', [ChildPresentController::class, 'index']);
Route::post('/new-wish', [ChildPresentController::class, 'store']);

Route::put('/update-present-type/{id}', [PresentTypeController::class, 'update']);
Route::put('/update-present/{id}', [PresentController::class, 'update']);
Route::put('/update-child/{id}', [ChildController::class, 'update']);
Route::delete('/delete-present-type/{id}', [PresentTypeController::class, 'destroy']);
Route::delete('/delete-present/{id}', [PresentController::class, 'destroy']);
Route::delete('/delete-child/{id}', [ChildController::class, 'destroy']);


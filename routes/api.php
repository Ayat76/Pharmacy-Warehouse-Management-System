<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\medicinesController;
use App\Http\Controllers\Web\WebMedicinesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
 return $request->user();
});
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
Route::middleware('auth:sanctum')->group( function () {
    Route::get('showMedicinesForClass',[RegisterController::class,'getMedicinesForClass']);
    Route::get('showClassifications',[RegisterController::class,'getClassifications']);
    Route::get('showMedicine',[RegisterController::class,'getMedicine']);
    Route::post('logout', [RegisterController::class,'logout']);
    Route::post('store',[WebMedicinesController::class,'store']);
    Route::post('classSearch',[medicinesController::class,'classSearch']);
    Route::post('medSearch',[medicinesController::class,'medSearch']);
    Route::post('storeOrder',[medicinesController::class,'storeOrder']);
    Route::get('showOrdersWeb',[medicinesController::class,'showOrdersWeb']);
    Route::get('showOrdersPharma',[medicinesController::class,'showOrdersPharma']);
    Route::get('showOneOrd',[medicinesController::class,'showOneOrd']);
    Route::post('update',[medicinesController::class,'update']);
});

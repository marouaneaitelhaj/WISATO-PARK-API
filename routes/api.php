<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkzoneController;
use App\Http\Controllers\UserclientController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});





Route::post('/register', [UserclientController::class, 'register']);
Route::post('/login', [UserclientController::class, 'login']);
Route::post('/updateProfile', [UserclientController::class, 'updateProfile']);


Route::get("readparkzones", [ParkzoneController::class, "readApi"]);
Route::get("readparkzones/{id}", [ParkzoneController::class, "readApiById"]);
Route::get("readparkzones/{id}/{cat}", [ParkzoneController::class, "readApiByIdAndCat"]);
Route::get("readparkzonestariff/{id}/{cat}", [ParkzoneController::class, "readTariffByIdAndCat"]);
Route::get("searchParkzones/{text?}", [ParkzoneController::class, "searchParkzones"]);

Route::get('/profileImage', [UserclientController::class, 'getProfileImage']);



Route::get('/user', [UserclientController::class, 'getUser'])->middleware('auth:sanctum');

// Route::get('readparkzones', 'ParkzoneController@readApi');
// Route::get('readparkzones/{id}', 'ParkzoneController@readApiById');
// Route::get('readparkzones/{id}/{cat}', 'ParkzoneController@readApiByIdAndCat');
// Route::get('readparkzonestariff/{id}/{cat}', 'ParkzoneController@readTariffByIdAndCat');


<?php

use App\Http\Controllers\ParkingsController;
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





Route::post('/register', [UserclientController::class, 'register']);
Route::post('/login', [UserclientController::class, 'login']);
Route::post('/updateProfile', [UserclientController::class, 'updateProfile']);


Route::get("readparkzones", [ParkzoneController::class, "readApi"]);
Route::get("readparkzones/{id}", [ParkzoneController::class, "readApiById"]);
Route::get("readparkzones/{id}/{cat}", [ParkzoneController::class, "readApiByIdAndCat"]);
Route::get("readparkzonestariff/{id}/{cat}", [ParkzoneController::class, "readTariffByIdAndCat"]);
Route::get("searchParkzones/{text?}", [ParkzoneController::class, "searchParkzones"]);
Route::get("slotbytypeandid/{type}/{id}", [ParkzoneController::class, "slotbytypeandid"]);


Route::get('/profileImage', [UserclientController::class, 'getProfileImage']);



Route::post('/parking', [ParkingsController::class, 'store']);
Route::get('/showparking/{user_id}', [ParkingsController::class, 'index']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::get('readparkzones', 'ParkzoneController@readApi');
// Route::get('readparkzones/{id}', 'ParkzoneController@readApiById');
// Route::get('readparkzones/{id}/{cat}', 'ParkzoneController@readApiByIdAndCat');
// Route::get('readparkzonestariff/{id}/{cat}', 'ParkzoneController@readTariffByIdAndCat');


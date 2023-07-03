<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserclientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/register', [UserclientController::class, 'showRegistrationForm'])->name('userclient.register.form');
Route::post('/register', [UserclientController::class, 'register'])->name('userclient.register');

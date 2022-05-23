<?php

use App\Http\Controllers\WisataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/wisata', [WisataController::class, 'index']);
Route::get('/wisata/{id}', [HomeController::class, 'lokasi']);

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Route::get('/wisata/{id}', [WisataController::class, 'lokasi']);
// Route::get('/wisata/{id}', [WisataController::class, 'index']);

Route::get('chart-js', [ChartJSController::class, 'index']);

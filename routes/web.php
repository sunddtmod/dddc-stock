<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontendController as fe;
use App\Http\Controllers\backendController as be;

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
    Route("home");
});
Route::get('/home', [fe::class, 'home'])->name('home');

Route::group(['middleware' => 'keycloak-web'], function () {

});
<?php

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

Route::get('/',            'App\Http\Controllers\ShopController@index');
Route::group(['middleware' => ['auth']], function(){
  Route::get('/mycart',      'App\Http\Controllers\ShopController@myCart');
  Route::post('/mycart',     'App\Http\Controllers\ShopController@addMyCart');
  Route::post('/cartdelete', 'App\Http\Controllers\ShopController@deleteCart');
  Route::post('/checkout',   'App\Http\Controllers\ShopController@checkout');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

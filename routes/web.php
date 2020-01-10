<?php

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

Route::get('/', 'BasicViewController@home')->name('home');



Route::get('/category/{category}', 'CategoryController@view')->name('category');
Route::get('/basket', 'ItemController@viewBasket')->name('basket');


Route::get('/prefill-items', 'PrefillController@items');
Route::get('/prefill-users', 'PrefillController@users');



Route::get('/login', 'BasicViewController@login')->name('login');
Route::get('/logout', 'AuthController@doLogout')->name('logout');

//Route::group(["name" => "api", "prefix" => "api."], function() {
Route::name('api.')->prefix('api')->group(function() {
    // Exposed APIs for front-end
    Route::post('/login', 'AuthController@doLogin')->name('doLogin');
    Route::middleware('auth')->group(function() {

        Route::put('/cart', 'ItemController@addToCart')->name('add-to-cart');
        Route::delete('/cart', 'ItemController@emptyCart')->name('empty-cart');
        Route::post('/orders', 'ItemController@order')->name('order');

        Route::post('/remove-from-cart', 'ItemController@removeCartItem')->name('remove-from-cart');
    });
});

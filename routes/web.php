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


Route::middleware('auth')->prefix('orders')->name('order.')->group(function() {
    // RESTful-like structure
    Route::get('/', 'OrderController@list')->name('list');
    Route::get('/{order}', 'OrderController@view')->name('view');
    Route::delete('/{order}', 'OrderController@delete')->name('delete');
    Route::post('/{order}', 'OrderController@alterQuantity')->name('alterQuantity');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {

    Route::name('item.')->prefix('items')->group(function(){
        Route::get('/', 'AdminItemController@list')->name('list');
        Route::get('/new', 'AdminItemController@creator')->name('creator');
        Route::post('/', 'AdminItemController@create')->name('create');
        Route::get('/{item}', 'AdminItemController@view')->name('view');
        Route::delete('/{item}', 'AdminItemController@delete')->name('delete');
        Route::post('/{item}/toggle', 'AdminItemController@toggle')->name('toggle');
    });
});

//Route::group(["name" => "api", "prefix" => "api."], function() {
Route::name('api.')->prefix('api')->group(function() {
    // Exposed APIs for front-end
    Route::post('/login', 'AuthController@doLogin')->name('doLogin');
    Route::middleware('auth')->group(function() {

        Route::put('/cart', 'ItemController@addToCart')->name('add-to-cart');
        Route::delete('/cart', 'ItemController@emptyBasket')->name('empty-cart');
        Route::post('/orders', 'ItemController@order')->name('order');

        Route::post('/remove-from-cart', 'ItemController@removeCartItem')->name('remove-from-cart');
    });
});

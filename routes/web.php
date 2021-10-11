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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//user email verification route
Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');

//goole login routes
Route::get('auth/google', 'Auth\RegisterController@redirectToProvider');
Route::get('auth/google/callback', 'Auth\RegisterController@handleProviderCallback');

//facebook login routes
Route::get('/auth/redirect/{provider}', 'Auth\RegisterController@facebookRedirect');
Route::get('auth/callback/{provider}', 'Auth\RegisterController@facebookCallback');

Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::match(['get','post'], 'products/store-multiple-images/{product_id}', 'ProductController@storeMultipleImages')->name('product.store-multiple-images');
    Route::get('products/delete-image/{id}','ProductController@deleteImage')->name('products.delete-image');
    Route::resource('products','ProductController');

});

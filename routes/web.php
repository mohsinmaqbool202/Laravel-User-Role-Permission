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


#Admin side routes
Route::get('admin-login', function () {
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



#user/customer side routes

#Home Page Routes
Route::get('/', 'IndexController@index');

#Product detail page
Route::get('product/{id}', 'IndexController@productDetail')->name('product.detail');

#user register /login page
Route::get('/login-register', 'CustomerController@customerLoginRegister')->name('login.register');

#register form submit
Route::post('/customer-register', 'CustomerController@register');

#activate account 
Route::get('/confirm/{code}', 'CustomerController@confirmAccount');

#user login route
Route::post('/customer-login', 'CustomerController@login');

#user logout route
Route::get('/customer-logout', 'CustomerController@logout');

#add to whishlist
Route::get('/add-to-wishlist', 'IndexController@addToWishList');
Route::get('/wish-list', 'IndexController@viewWishList');
Route::get('/delete-wish/{id}', 'IndexController@deleteWishList');



<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

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
    
    Route::post('login', 'UserController@login');
    Route::post('signup', 'UserController@signup');


Route::group(['middleware' => 'auth:api'], function() {

    Route::get('users', 'UserController@index');
    Route::post('users/create', 'UserController@store');
    Route::get('user/{id}', 'UserController@show');
    Route::get('users/delete/{user}', 'UserController@destroy');

    Route::get('logout', 'UserController@logout');

});


<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login')->middleware('throttle:6,1');
    Route::post('signup', 'AuthController@signup')->middleware('throttle:10,1');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate')->middleware('throttle:3,1');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user')->middleware('client');
    });
});
//reset password
Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api', 'throttle:10,1',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

Route::group([
    'prefix' => 'Security',
    'middleware' => 'auth:api',
    'namespace' => 'Security']
    , function () {
        Route::get('Users','UsersController@index');
        //Route::post('Users/create','UsersController@create');
        Route::get('Users/edit/{id}','UsersController@edit')->name('editUser');
        Route::post('Users/update/{id}','UsersController@update')->name('updateUser');
        Route::post('Users/desactivate/{id}','UsersController@desactivate')->name('desactivateUser');
        Route::post('Users/activate/{id}','UsersController@activate')->name('activateUser');
        Route::post('Users/delete/{id}','UsersController@delete')->name('deleteUser');

        Route::get('Roles','RoleController@index');
        Route::post('Roles/create','RoleController@create');
        Route::get('Roles/edit/{id}','RoleController@edit');
        Route::post('Roles/update/{id}','RoleController@update');
        Route::post('Roles/desactivate/{id}','RoleController@desactivate');
        Route::post('Roles/activate/{id}','RoleController@activate');
        Route::post('Roles/delete/{id}','RoleController@delete');
    }
);
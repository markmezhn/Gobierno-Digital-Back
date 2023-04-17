<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('v1')->group(function(){
            Route::post('login', 'LoginController@login');
    
            Route::group(['middleware' => 'auth:api'], function() {
                //User Routes
                Route::get('/users', 'UserController@getUsers');
                Route::get('/user/{id}', 'UserController@getUser');
                Route::post('user', 'UserController@createUser');
                Route::put('/user/{id}', 'UserController@updateUser');
                Route::delete('/user/{id}', 'UserController@deleteUser');

                Route::get('/roles', 'RoleController@getRoles');
                Route::get('/role/{id}', 'RoleController@getRole');
                Route::post('role', 'RoleController@createRole');
                Route::put('/role/{id}', 'RoleController@updateRole');
                Route::delete('/role/{id}', 'RoleController@deleteRole');
                
                Route::post('change-password', 'UserController@changePassword');
                //Route::get('/rolesuser', 'RoleController@getRolesuser');
        
            });
    });
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'login', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/list', 'LoginController@index'); // não exige request - retorna todos os usuarios
    Route::get('/', 'LoginController@show'); // request (email, password) - retorna condição de autenticado ou não
    Route::post('/', 'LoginController@store'); // request (name, email, password, password_confirm) - para criar um novo usuario
    Route::put('/', 'LoginController@update'); // request (email) e a informações de desejo para atualização do usuario sendo (name e/ou password)
    Route::delete('/', 'LoginController@destroy'); // request (email) - deleta um usuario da base.
});

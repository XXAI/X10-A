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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/credencializacion','AsistenciaController@show');

Route::get('/consulta-asistencia','API\reporteController@consulta_checadas');

Route::get('reporte','API\reporteController@index');

Route::get('repgral','API\reporteGralController@index');
Route::get('kardex','API\kardexController@index');#->name('kardex');
Route::get('/kardex2','API\kardexController@show')->name('repkardex');
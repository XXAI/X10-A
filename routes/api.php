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

Route::get('/kardex','API\KardexController@index');

Route::get('/mensual','API\ReporteMensualController@index');
Route::get('/trimestral','API\ReporteTrimestralController@index');
Route::get('/empleado','API\EmpleadoController@index');
//Route::post('/empleado','API\EmpleadoController@llenarSelect');


Route::get('/catalogo','API\ReporteMensualController@catalogo');
Route::get('/reporte-mensual','API\ReporteMensualController@reporteMensual');
Route::get('/reporte-mensual-8002','API\ReporteMensualController@reporteMensual_8002');
Route::get('/reporte-trimestral','API\ReporteTrimestralController@reporteTrimestral');
Route::post('/guarda-justificante','API\DiasJustificaController@store');
Route::post('/guarda-entrasal','API\EntraSalidaController@store');
Route::post('login', 'API\LoginController@login');
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
    /*return $request->user();
});*/

    Route::get('/credencializacion','AsistenciaController@show');

    Route::get('/consulta-asistencia','API\reporteController@consulta_checadas');

    Route::get('reporte','API\reporteController@index');

    Route::get('repgral','API\reporteGralController@index');

    Route::get('/kardex','API\KardexController@index');

    Route::get('/mensual','API\ReporteMensualController@index');
    Route::get('/trimestral','API\ReporteTrimestralController@index');
    Route::get('/empleado','API\EmpleadoController@index');
    Route::post('/empleado/fetch','API\EmpleadoController@fetch');
    Route::post('/empleado/tipoincidencia','API\EmpleadoController@tipoincidencia');


    Route::get('/catalogo','API\ReporteMensualController@catalogo');
    Route::get('/reporte-mensual','API\ReporteMensualController@reporteMensual');
    Route::get('/reporte-mensual-8002','API\ReporteMensualController@reporteMensual_8002');
    Route::get('/reporte-trimestral','API\ReporteTrimestralController@reporteTrimestral');


    Route::get('/buscaempleado/{id}', 'API\EmpleadoController@show');

    Route::get('/buscaincidencia/{id}', 'API\IncidenciaController@show');
    Route::get('/justificante/{id}',    'API\IncidenciaController@justificante');
    Route::get('/validaincidencia/{id}', 'API\DiasJustificaController@update');
    /* Cardex */
    Route::get('/cardex','API\CardexController@index');
    Route::get('/reporte-cardex','API\CardexController@reporteCardex');
    /* */


    Route::post('/guarda-justificante','API\DiasJustificaController@store');
    Route::post('/guarda-just-emp','API\IncidenciaController@store');
    Route::post('/guarda-entrasal','API\EntraSalidaController@store');
    Route::post('/guarda-empleado','API\EmpleadoController@store');
    Route::get('/edita-empleado/{id}', 'API\EmpleadoController@update');
    Route::get('/hora-empleado/{id}', 'API\EmpleadoController@modifica_horario_empleado');
    Route::get('/ver-configuracion-trimestral', 'API\ConfiguracionTrimestralController@show');
    Route::post('/guarda-configuracion-trimestral','API\ConfiguracionTrimestralController@store');
    Route::delete('/deleteincidencia/{id}', 'API\DiasJustificaController@destroy');
    Route::delete('/deleteinci-emp/{id}', 'API\IncidenciaController@destroy');
//});
Route::post('login', 'Auth\LoginController@doLogin');
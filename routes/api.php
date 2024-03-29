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
    Route::get('/direccion','API\ReporteDireccionController@index');
    Route::get('/trimestral','API\ReporteTrimestralController@index');
    Route::get('/empleado','API\EmpleadoController@index');
    Route::post('/empleado/fetch','API\EmpleadoController@fetch');
    Route::post('/buscacapturista','API\LogsController@buscacapturista');
    Route::post('/buscaempleado','API\LogsController@buscaempleado');
   
   
    Route::post('/empleado/tipoincidencia','API\EmpleadoController@tipoincidencia');


    Route::get('/catalogo','API\ReporteMensualController@catalogo');
    
    Route::get('/reporte-mensual','API\ReporteMensualController@reporteMensual');
    Route::get('/reporte-capturista','API\LogsController@reporteCapturista');
    Route::get('/reporte-incidencias','API\LogsController@reporteIncidencia');
    Route::get('/export','API\LogsController@export');
    Route::get('/reporte-direccion','API\ReporteDireccionController@reporteDireccion');
    Route::get('/reporte-mensual-8002','API\ReporteMensualController@reporteMensual_8002');
    Route::get('/reporte-trimestral','API\ReporteTrimestralController@reporteTrimestral');
    Route::get('/imprimirTarjeta','API\reporteController@imprimirTarjeta');

    Route::get('/cat-base','API\EmpleadoController@catalogo_bases');
    Route::get('/buscaempleado/{id}', 'API\EmpleadoController@show');    
    Route::get('/omisiones', 'API\EmpleadoController@omisiones'); 
    Route::get('/economicos', 'API\EmpleadoController@buscaEconomico');  
    Route::get('/permisos', 'API\EmpleadoController@buscapermiso');
    Route::get('/pases', 'API\EmpleadoController@buscapases');
    Route::get('/permisos_empleados', 'API\EmpleadoController@permisos_empleados');
    Route::get('/buscaincidencia/{id}', 'API\IncidenciaController@show');
    Route::get('/justificante/{id}',    'API\IncidenciaController@justificante');
    Route::get('/validaincidencia/{id}', 'API\DiasJustificaController@update');
    /* Cardex */
    Route::get('/cardex','API\CardexController@index');
    Route::get('/logs','API\LogsController@obtenerLogs');
    Route::get('/incidencias','API\LogsController@obtenerIncidencias');
    Route::get('/checadas','API\LogsController@obtenerchecadas');
    Route::get('/reporte-cardex','API\CardexController@reporteCardex');
    /* */

    Route::post('/registra-usuario','Auth\RegisterController@store');
    Route::post('/guarda-justificante','API\DiasJustificaController@store');
    Route::post('/guarda-just-emp','API\IncidenciaController@store');
    Route::post('/guarda-entrasal','API\EntraSalidaController@store');
    Route::post('/guarda-empleado','API\EmpleadoController@store');
    Route::get('/edita-empleado/{id}', 'API\EmpleadoController@update');
    Route::get('/hora-empleado/{id}', 'API\EmpleadoController@modifica_horario_empleado');
    Route::get('/ver-configuracion-trimestral', 'API\ConfiguracionTrimestralController@show');
    Route::post('/guarda-configuracion-trimestral','API\ConfiguracionTrimestralController@store');
    Route::delete('/deleteincidencia/{id}', 'API\DiasJustificaController@destroy');
    Route::delete('/deleteomision/{id}', 'API\EntraSalidaController@destroy');
    Route::delete('/deleteinci-emp/{id}', 'API\IncidenciaController@destroy');
    Route::delete('/deletehora-emp/{id}', 'API\EmpleadoController@elimina_horario');
//});
Route::post('login', 'Auth\LoginController@doLogin');
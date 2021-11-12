<?php

Route::group(['middleware' => 'web'], function () {
    Route::get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@showLogin']);    
    Route::post('sign-in','Auth\LoginController@doLogin');
    
    //Route::middleware('auth')->get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

   
   /*
    Route::middleware('auth')->get('/dashboard', 'DashboardController@index');
    Route::middleware('auth')->get('/reporte-mensual', 'DashboardController@mensual');
    Route::middleware('auth')->get('/reporte-direccion', 'DashboardController@direccion');
    Route::middleware('auth')->get('/reporte-capturistas', 'DashboardController@capturistas');
    Route::middleware('auth')->get('/reporte-trimestral', 'DashboardController@trimestral');
    Route::middleware('auth')->get('/empleado', 'DashboardController@empleado');
    Route::middleware('auth')->get('/register', 'DashboardController@register');

    Route::middleware('auth')->get('/cardex', 'DashboardController@cardex');
    Route::middleware('auth')->get('/checadas', 'DashboardController@checadas');
    Route::middleware('rutabase')->get('//consulta-asistencia', 'API\reporteController@consulta_checadas');*/
    Route::group(['middleware' => 'auth'], function() {
        
        Route::get('/', function () { return Redirect::to('login'); });        
        Route::get('/dashboard', 'DashboardController@index');
        Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

        
        Route::get('/reporte-mensual', 'DashboardController@mensual');
        Route::get('/reporte-direccion', 'DashboardController@direccion');
        Route::get('/reporte-capturistas', 'DashboardController@capturistas');
        Route::get('/reporte-incidencias', 'DashboardController@incidencias');
        Route::get('/reporte-trimestral', 'DashboardController@trimestral');
        Route::get('/empleado', 'DashboardController@empleado');
        Route::get('/register', 'DashboardController@register');
    
        Route::get('/cardex', 'DashboardController@cardex');
        Route::get('/checadas', 'DashboardController@checadas');
        
    });
     Route::view('/asistencia/{rfc}','infoRh');
    Route::middleware('rutabase')->get('//consulta-asistencia', 'API\reporteController@consulta_checadas');
});
<?php


Route::get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@showLogin']);
Route::get('/', function () { return Redirect::to('login'); });
Route::post('sign-in','Auth\LoginController@doLogin');
Route::middleware('auth')->get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

Route::view('/asistencia/{rfc}','infoRh');
Route::view('/kardex','reportes/kardex');
Route::view('/kardex/{id}','reportes/kardexUsuario');

Route::view('/prueba','prueba2');


Route::get('/repgral','reporteGralController@index');

//Route::view('/reporte-mensual','reportes/mensual');
//Route::view('/reporte-pdf-mensual','reportes/reporte-mensual');


Route::middleware('auth')->get('/dashboard', 'DashboardController@index');
Route::middleware('auth')->get('/reporte-mensual', 'DashboardController@mensual');
//Route::middleware('auth')->get('/reporte-trimestral', 'DashboardController@trimestral');

Route::get('/reporte-trimestral', 'DashboardController@trimestral');
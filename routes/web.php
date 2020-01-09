<?php

Route::view('/','home');
//ASI QUEDARA PARA CONSULTAR EN INDUCCION
Route::view('/asistencia/{rfc}','infoRh');
Route::view('/kardex','reportes/kardex');
Route::view('/kardex/{id}','reportes/kardexUsuario');


Route::get('/repgral','reporteGralController@index');

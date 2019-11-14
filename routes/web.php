<?php

Route::view('/','home');
//ASI QUEDARA PARA CONSULTAR EN INDUCCION
Route::view('/asistencia/{rfc}','infoRh');


Route::get('/repgral','reporteGralController@index');

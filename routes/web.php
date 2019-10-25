<?php

Route::view('/','home')->name('home');
Route::view('/about','about')->name('about');
Route::resource('portfolio', 'ProjectController')->names('projects')->parameters(['portfolio' => 'project']);

//ASI QUEDARA PARA CONSULTAR EN INDUCCION

Route::view('/asistencia/{rfc}','infoRh');
Route::get('/API/reporte','reporteController@index');




Route::post('contact', 'MessageController@store')->name('messages.store');

Auth::routes(['register' => false]);



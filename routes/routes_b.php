<?php
// ========================================================================================
//                                          USUARIOS
// ========================================================================================
// VER INDEX DE USUARIOS
Route::get('/users','UserController@index')->name('users.index');
Route::get('/users/show/{id}','UserController@show')->name('users.show');
Route::get('/usuario/export', 'UserController@export')->name('users.export');
Route::get('/users/create','UserController@create')->name('users.create');
Route::post('/users/store','UserController@store')->name('users.store');
Route::get('/users/create','UserController@create')->name('users.create');
Route::post('/users/store','UserController@store')->name('users.store');
Route::get('/users/{user}/edit','UserController@edit')->name('users.edit');
Route::post('/users/{user}','UserController@update')->name('users.update');
Route::post('/users_delete/{user}','UserController@destroy')->name('users.destroy');
Route::get('/users/modalDelete/{id}', 'UserController@modalDelete')->name('users.modalDelete');
Route::get('/users/modalCambEstado/{id}', 'UserController@modalCambioEstado')->name('users.modalState');
Route::post('/users/cambiarestado/{id}','UserController@cambiarestado')->name('users.cambiarestado');
Route::get('/users_privilegios/{user}/edit','UserController@privilegios')->name('users.privilegios');
Route::put('/users/privilegios/{user}','UserController@privilegiosupdate')->name('users.privilegiosupdate');
Route::put('/users/{user}/rol','UserController@updaterol')->name('updaterol');
Route::get('/perfil_usuario','UserController@perfil')->name('perfil');
Route::post('updateprofile/{user}','UserController@updateprofile')->name('updateprofile');
Route::post('/useravatar', 'UserController@uploadAvatarImagen')->name('users.avatar');
Route::post('/validar_user','UserController@validarUsername')->name('users.validar');


// ========================================================================================
//                                      CLIENTES
// ========================================================================================
Route::get('/clients', 'ClientsController@index')->name('clients.index');
Route::get('/clients/show/{id}', 'ClientsController@show')->name('clients.show');
Route::post('/table_clients', 'ClientsController@tableClients')->name('clients.table');

Route::get('/clients/estadomodal/{id}/{estado}', 'ClientsController@modalEstado')->name('clients.modalEstado');
Route::get('/clients/estado/{id}/{estado}', 'ClientsController@changeEstado')->name('clients.cambioEstado');

Route::get('/clients/modalCreate', 'ClientsController@modalCreate')->name('cliente.create');
Route::post('/store_clients', 'ClientsController@store')->name('clients.store');
Route::get('/clients/editmodal/{id}', 'ClientsController@modalEdit')->name('clients.editmodal');
Route::post('/clients/update/{id}', 'ClientsController@update')->name('clients.update');
Route::post('/clients_avatar', 'ClientsController@uploadAvatar')->name('clients.avatar');
Route::get('/clients/modaldelete/{id}', 'ClientsController@modalDeleteAvatar')->name('clients.modalDeleteAvatar');
Route::delete('/clients/delete_avatar/{id}', 'ClientsController@destroyAvatar')->name('clients.destroyAvatar');

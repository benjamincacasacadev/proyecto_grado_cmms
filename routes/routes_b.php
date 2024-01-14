<?php
Route::get('/list_of_clients', 'ClientsController@listClients')->name('clients.listClients');
Route::get('/list_of_assets_details', 'StAssetsController@listAssetsDetailsAjax')->name('assets.listAssets.details');

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
Route::post('/table_clients', 'ClientsController@tableClients')->name('clients.table');
Route::get('/clients/estado/{id}/{estado}', 'ClientsController@changeEstado')->name('clients.cambioEstado');
Route::get('/clients/modalCreate', 'ClientsController@modalCreate')->name('clientS.createmodal');
Route::post('/store_clients', 'ClientsController@store')->name('clients.store');
Route::get('/clients/editmodal/{id}', 'ClientsController@modalEdit')->name('clients.editmodal');
Route::post('/clients/update/{id}', 'ClientsController@update')->name('clients.update');
Route::get('/clients/deletemodal/{id}', 'ClientsController@modalDelete')->name('clients.deletemodal');
Route::delete('/clients/delete/{id}','ClientsController@destroy')->name('clients.destroy');

// ========================================================================================
//                                      ACTIVOS
// ========================================================================================
Route::get('/assets', 'StAssetsController@index')->name('assets.index');
Route::post('/table_assets', 'StAssetsController@tableAssets')->name('assets.table');
Route::get('/assets/estado/{id}/{estado}', 'StAssetsController@changeEstado')->name('assets.cambioEstado');
Route::get('/assets/modalCreate', 'StAssetsController@modalCreate')->name('assets.createmodal');
Route::post('/store_assets', 'StAssetsController@store')->name('assets.store');
Route::get('/assets/editmodal/{id}', 'StAssetsController@modalEdit')->name('assets.editmodal');
Route::post('/assets/update/{id}', 'StAssetsController@update')->name('assets.update');
Route::get('/assets/deletemodal/{id}', 'StAssetsController@modalDelete')->name('assets.deletemodal');
Route::delete('/assets/delete/{id}','StAssetsController@destroy')->name('assets.destroy');

// ========================================================================================
//                                   ORDENES DE TRABAJO
// ========================================================================================
Route::get('/work_orders', 'WorkOrdersController@index')->name('workorders.index');
Route::get('/work_orders/show/{id}', 'WorkOrdersController@show')->name('workorders.show');
Route::post('/table_work_orders', 'WorkOrdersController@tableWorkorders')->name('workorders.table');
Route::get('/work_orders/create', 'WorkOrdersController@create')->name('workorders.createmodal');
Route::post('/store_work_orders', 'WorkOrdersController@store')->name('workorders.store');
Route::get('/work_orders/editmodal/{id}', 'WorkOrdersController@modalEdit')->name('workorders.editmodal');
Route::post('/work_orders/update/{id}', 'WorkOrdersController@update')->name('workorders.update');
Route::get('/work_orders/deletemodal/{id}', 'WorkOrdersController@modalDelete')->name('workorders.deletemodal');
Route::delete('/work_orders/delete/{id}','WorkOrdersController@destroy')->name('workorders.destroy');

// ========================================================================================
//                                   ORDENES DE TRABAJO
// ========================================================================================
Route::get('/forms', 'StFormController@index')->name('forms.index');
Route::post('/table_forms', 'StFormController@tableForms')->name('forms.table');

Route::get('/forms/modalCreate', 'StFormController@modalCreate')->name('forms.createmodal');
Route::post('/store_forms', 'StFormController@store')->name('forms.store');
Route::get('/forms/editmodal/{id}', 'StFormController@modalEdit')->name('forms.editmodal');
Route::post('/forms/update/{id}', 'StFormController@update')->name('forms.update');
Route::get('/forms/deletemodal/{id}', 'StFormController@modalDelete')->name('forms.deletemodal');
Route::delete('/forms/delete/{id}','StFormController@destroy')->name('forms.destroy');
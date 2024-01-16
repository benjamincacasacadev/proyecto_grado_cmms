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
Route::get('/workorders/report/{id}', 'WorkOrdersController@report')->name('reports.show');
Route::post('/work_orders/updateImage/{id}', 'WorkOrdersController@updateImage')->name('workorders.updateImage');

// GUARDAR LOS INTERVALOS DE FECHAS DE LAS ORDENES DE TRABAJO
Route::post('/work_orders/time_range/{id}', 'WorkOrdersController@timeRangeStore')->name('workorders.timeRangeStore');
// Iniciar temporizador de trabajo
Route::post('/work_orders/worktime/{id}', 'WorkOrdersController@initTimeWork')->name('workorders.worktime');

// Tiempos de trabajo
Route::get('/work_orders/time/modalDuration/{id}', 'WorkOrdersController@modalDuration')->name('workorders.modalDuration');

// llenar Informes
Route::post('/work_orders/show/update/{id}', 'WorkOrdersController@updateReport')->name('workorders.updateReport');
Route::get('/work_orders/export/{id}', 'WorkOrdersController@export')->name('workorders.pdf');
Route::get('/work_orders/modalSendRevision/{id}/{swC}', 'WorkOrdersController@modalSendRevision')->name('reports.modalsendrevision');
Route::put('/work_orders/send/revision/{id}/{swC?}', 'WorkOrdersController@SendRevision')->name('reports.sendrevision');

// Archivos
Route::post('/reports_tablefile', 'WorkOrdersController@tableFile')->name('reports.tableFiles');
Route::post('/work_orders/storefile', 'WorkOrdersController@storeFile')->name('reports.saveFile');
Route::post('/work_orders/orderfile', 'WorkOrdersController@orderFile')->name('reports.orderFile');
Route::post('/st_report_update', 'WorkOrdersController@updateNombreArchivo')->name('streports.updatearchivo');
Route::get('/reports/mostrarImagen/{id}', 'WorkOrdersController@modalShowFile')->name('reports.modalFile');
Route::get('/reports/downloadfile/{img}/{cod}', 'WorkOrdersController@downloadFile')->name('reports.downloadFile');
Route::get('/reports/attachFile/{id}', 'WorkOrdersController@attachFile')->name('reports.attachfile');
Route::get('/reports/deleteModalFile/{id}/{cod}', 'WorkOrdersController@modalDeleteFile')->name('reports.modalDestroyFile');
Route::delete('/reports/deletefile/{img}/{cod}', 'WorkOrdersController@destroyFile')->name('reports.destroyFile');

Route::put('/reports/reject/revision/{id}', 'WorkOrdersController@rejectRevision')->name('reports.rejectrevision');
Route::post('/reports/validate/revision/{id}', 'WorkOrdersController@validateRevision')->name('reports.validaterevision');

// ========================================================================================
//                                   FORMULARIOS
// ========================================================================================
Route::get('/forms', 'StFormController@index')->name('forms.index');
Route::post('/table_forms', 'StFormController@tableForms')->name('forms.table');

Route::get('/forms/modalCreate', 'StFormController@modalCreate')->name('forms.createmodal');
Route::post('/store_forms', 'StFormController@store')->name('forms.store');
Route::get('/forms/editmodal/{id}', 'StFormController@modalEdit')->name('forms.editmodal');
Route::post('/forms/update/{id}', 'StFormController@update')->name('forms.update');
Route::get('/forms/deletemodal/{id}', 'StFormController@modalDelete')->name('forms.deletemodal');
Route::delete('/forms/delete/{id}','StFormController@destroy')->name('forms.destroy');

// Contenedores
Route::get('/forms/container/{id}', 'StFormController@indexContainer')->name('forms.container');
// Mantenimiento
Route::get('/forms/maintenance/{id}', 'StFormController@indexMaintenance')->name('forms.maintenance');
// PDF
Route::get('/forms/export/{id}', 'StFormController@export')->name('forms.pdf');
// Carta
Route::get('/forms/letter/{id}', 'StFormController@indexLetter')->name('forms.letter');

// ADMINISTRAR
// Contenedores
Route::post('/storecontainer/{id}', 'StFormController@storeContainer')->name('container.store');
Route::get('/forms/container/editmodal/{idcont}/{id}/', 'StFormController@modalEditContainer')->name('container.editmodal');
Route::post('/forms/container/update/{idcont}/{id}/', 'StFormController@updateContainer')->name('forms.container.update');
Route::get('/forms/subcontainer/editmodal/{idcont}/{idsubc}/{id}/', 'StFormController@modalEditSubContainer')->name('subcontainer.editmodal');
Route::post('/forms/subcontainer/update/{idcont}/{idsubc}/{id}/', 'StFormController@updateSubContainer')->name('forms.subcontainer.update');
Route::get('/forms/container/ordermodal/{id}/', 'StFormController@modalOrderContainer')->name('container.ordermodal');
Route::post('/forms/container/orderUpdate/', 'StFormController@ajaxOrderContainer')->name('forms.orderContainer');
Route::get('/forms/subcontainer/ordermodal/{idcont}/{id}/', 'StFormController@modalOrderSubContainer')->name('subcontainer.ordermodal');
Route::post('/forms/subcontainer/orderUpdate/', 'StFormController@ajaxOrderSubContainer')->name('forms.orderSubContainer');
Route::delete('/forms/container/destroy/{idcont}/{idsubc}/{id}/{sw}', 'StFormController@destroyContainer')->name('container.destroy');
Route::get('/forms/container/deletemodal/{idcont}/{idsubc}/{id}/{sw}', 'StFormController@modalDeleteContainer')->name('container.deletemodal');
Route::delete('/forms/container/destroy/{idcont}/{idsubc}/{id}/{sw}', 'StFormController@destroyContainer')->name('container.destroy');
Route::post('/forms/contAjax/{id}', 'StFormController@ajaxSelectCont')->name('forms.contajax');
Route::post('/forms/subcontAjax/{id}', 'StFormController@ajaxSelectSubCont')->name('forms.subcontajax');
// Mantenimiento
Route::post('/forms/subcontainerAjax/', 'StFormController@ajaxSubcontainer')->name('forms.subcontainerajax');
Route::post('/forms/selectdepAjax/', 'StFormController@ajaxSelectDependiente')->name('forms.selectDepAjax');
Route::post('/storemaintenance/{id}', 'StFormController@storeMaintenance')->name('maintenance.store');
Route::post('/storemaintenance/{id}', 'StFormController@storeMaintenance')->name('maintenance.store');
// Editar campos de procedimientos
Route::get('/forms/maintenance/editmodal/{idcampo}/{id}', 'StFormController@modalEditMaintenance')->name('maintenance.editmodal');
Route::post('/forms/maintenance/updateTextfield/{idcampo}/{id}/', 'StFormController@updateTextfield')->name('forms.textfield.update');
Route::post('/forms/maintenance/updateRadiofield/{idcampo}/{id}/', 'StFormController@updateRadiofield')->name('forms.radiofield.update');
Route::post('/forms/maintenance/updateCheckfield/{idcampo}/{id}/', 'StFormController@updateCheckfield')->name('forms.checkfield.update');
Route::post('/forms/maintenance/updateSelectfield/{idcampo}/{id}/', 'StFormController@updateSelectfield')->name('forms.selectfield.update');
Route::post('/forms/maintenance/updateSeriefield/{idcampo}/{id}/', 'StFormController@updateSeriefield')->name('forms.seriefield.update');
Route::get('/forms/maintenance/deletemodal/{idcampo}/{id}', 'StFormController@modalDeleteMaintenance')->name('maintenance.deletemodal');
Route::delete('/forms/maintenance/destroy/{idcampo}/{id}', 'StFormController@destroyMaintenance')->name('maintenance.destroy');
// Graficos y series
Route::get('/forms/series/modalcreate/{id}', 'StFormController@modalCreateSerie')->name('forms.serie.modalcreate');
Route::post('/forms/storeSerie/{id}', 'StFormController@storeSerie')->name('forms.serie.store');

Route::post('/storeletter/{id}', 'StFormController@storeLetter')->name('letter.store');
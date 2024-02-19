<?php
Route::get('/list_of_clients', 'ClientsController@listClients')->name('clients.listClients');
Route::get('/list_of_assets_details', 'StAssetsController@listAssetsDetailsAjax')->name('assets.listAssets.details');
Route::get('/list_of_work_orders', 'WorkOrdersController@listWorkOrdersAjax')->name('workorders.listWorkOrdersAjax'); // LISTADO DE ORDENES DE TRABAJO POR SELECT 2 AJAX

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
Route::post('/userfirma', 'UserController@uploadFirmaImagen')->name('users.firma');

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
Route::get('/assets/show/{id}', 'StAssetsController@show')->name('assets.show');
Route::post('/table_assets', 'StAssetsController@tableAssets')->name('assets.table');
Route::get('/assets/estado/{id}/{estado}', 'StAssetsController@changeEstado')->name('assets.cambioEstado');
Route::get('/assets/modalCreate', 'StAssetsController@modalCreate')->name('assets.createmodal');
Route::post('/store_assets', 'StAssetsController@store')->name('assets.store');
Route::get('/assets/editmodal/{id}', 'StAssetsController@modalEdit')->name('assets.editmodal');
Route::post('/assets/update/{id}', 'StAssetsController@update')->name('assets.update');
Route::get('/assets/deletemodal/{id}', 'StAssetsController@modalDelete')->name('assets.deletemodal');
Route::delete('/assets/delete/{id}','StAssetsController@destroy')->name('assets.destroy');
Route::post('/assets/updateImage/{id}', 'StAssetsController@updateImage')->name('assets.updateImage');

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
Route::post('/schedule/ajax/ot', 'StScheduleController@datesAjax')->name('calendario.ots');

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


// ========================================================================================
//                                      INVENTARIO
// ========================================================================================
Route::get('/inventory', 'InventoryController@index')->name('inventory.index');
Route::post('/inventory/table', 'InventoryController@tableInventory')->name('inventory.table');
Route::get('/inventory/imagemodal/{id}/{sw}', 'InventoryController@modalImage')->name('inventory.imagemodal');
// ITEMS MODAL
Route::get('/items/modal/', 'InventoryController@modalItems')->name('items.modalitems');
Route::post('/items/table', 'InventoryController@tableItems')->name('items.table');
Route::get('/inventory/createmodal', 'InventoryController@modalCreate')->name('inventory.createmodal');
Route::post('/inventory/store', 'InventoryController@store')->name('inventory.store');
Route::get('/items/state/{id}/{estado}', 'InventoryController@changeStatus')->name('inventory.changestatus');
Route::get('/inventory/editmodal/{id}', 'InventoryController@modalEdit')->name('inventory.editmodal');
Route::post('/inventory/update/{id}', 'InventoryController@update')->name('inventory.update');
Route::get('/inventory/deletemodal/{id}', 'InventoryController@modalDelete')->name('inventory.deletemodal');
Route::delete('/inventory/destroy/{id}', 'InventoryController@destroy')->name('inventory.destroy');
Route::post('/inventory/updateImage/{id}', 'InventoryController@updateImage')->name('inventory.updateImage'); // Update imagen

// KARDEX (SHOW)
Route::get('/inventory/kardex/{id}', 'InventoryController@kardex')->name('inventory.kardex');
Route::post('/inventory/details/table', 'InventoryController@tableKardexDetails')->name('inventory.details.table');

// ========================================================================================
//                                  NOTAS DE INGRESO
// ========================================================================================
Route::get('/incomes', 'InvIncomesController@index')->name('incomes.index');
Route::post('/incomes/table', 'InvIncomesController@tableIncomes')->name('incomes.table');
Route::get('/incomes/{id}', 'InvIncomesController@show')->name('incomes.show');
Route::post('/incomes/details/table', 'InvIncomesDetailsController@tableDetails')->name('incomes.details.table');

Route::post('/incomes/store', 'InvIncomesController@store')->name('incomes.store');
Route::get('/incomes/editmodal/{id}', 'InvIncomesController@modalEdit')->name('incomes.editmodal');
Route::post('/incomes/update/{id}', 'InvIncomesController@update')->name('incomes.update');
Route::get('/incomes/deletemodal/{id}', 'InvIncomesController@modalDelete')->name('incomes.deletemodal');
Route::delete('/incomes/delete/{id}', 'InvIncomesController@destroy')->name('incomes.destroy');

// Detalles de ingreso
Route::get('/incomes/statemodal/{id}', 'InvIncomesController@modalState')->name('incomes.statemodal');
Route::post('/incomes/state/{id}', 'InvIncomesController@updateState')->name('incomes.state');

Route::post('/incomes/details/store/{id}', 'InvIncomesDetailsController@storeDetails')->name('incomes.details.store');
Route::get('/incomes/details/editmodal/{id}/{sw}', 'InvIncomesDetailsController@modalEdit')->name('incomes.details.editmodal');
Route::post('/incomes/details/update/{id}', 'InvIncomesDetailsController@updateDetails')->name('incomes.details.update');
Route::get('/incomes/details/deletemodal/{id}/{sw}', 'InvIncomesDetailsController@modalDelete')->name('incomes.details.deletemodal');
Route::delete('/incomes/details/destroy/{id}', 'InvIncomesDetailsController@destroyDetails')->name('incomes.details.destroy');
Route::get('/incomes/details/order', 'InvIncomesDetailsController@order')->name('incomes.details.order');

// ITEMS MODAL
Route::get('/items/modal/', 'InventoryController@modalItems')->name('items.modalitems');
Route::post('/items/table', 'InventoryController@tableItems')->name('items.table');
Route::get('/incomes/items/modal/', 'InvIncomesDetailsController@modalItems')->name('items.incomes.modalitems'); // Create transfers
Route::post('/items/incomes/table', 'InvIncomesDetailsController@tableItemsEdit')->name('items.incomes.table');


// ========================================================================================
//                                      TRASPASOS
// ========================================================================================
Route::get('/transfers', 'InvTransfersController@index')->name('transfers.index');
Route::post('/transfers/table', 'InvTransfersController@tableTransfers')->name('transfers.table');
Route::get('/transfers/showmodal/{id}', 'InvTransfersController@modalShow')->name('transfers.showmodal');

Route::get('/transfers/createmodal', 'InvTransfersController@modalCreate')->name('transfers.createmodal');
Route::post('/transfers/store/', 'InvTransfersController@store')->name('transfers.store');
Route::post('/transfers/locationsAJAX/', 'InvTransfersController@ajaxLocations')->name('transfers.locations.ajax');
// Traspasos desde pedidos
Route::get('/items/transfer/{id}', 'InvStocksController@modalTransferItems')->name('items.transfermodal');
Route::post('/items/transfer/update/{id}', 'InvStocksController@updateTransferItems')->name('items.transfers.update');

Route::get('/transfers/statemodal/{id}', 'InvTransfersController@modalState')->name('transfers.statemodal');
Route::post('/transfers/state/{id}', 'InvTransfersController@updateState')->name('transfers.state');

// ========================================================================================
//                                      PEDIDOS
// ========================================================================================
Route::get('/outcomes', 'InvOutcomesController@index')->name('outcomes.index');
Route::post('/outcomes/table', 'InvOutcomesController@tableOutcomes')->name('outcomes.table');
Route::get('/outcomes/show/{id}', 'InvOutcomesController@show')->name('outcomes.show');
Route::post('/outcomes/details/table', 'InvOutcomesDetailsController@tableDetails')->name('outcomes.details.table');
Route::post('/outcomes/reportClient/', 'InvOutcomesController@ajaxClientWorkorders')->name('outcomes.workorderclientajax');
Route::get('/outcomes/createmodal', 'InvOutcomesController@modalCreate')->name('outcomes.createmodal');
Route::post('/outcomes/store', 'InvOutcomesController@store')->name('outcomes.store');
Route::get('/outcomes/editmodal/{id}', 'InvOutcomesController@modalEdit')->name('outcomes.editmodal');
Route::post('/outcomes/update/{id}', 'InvOutcomesController@update')->name('outcomes.update');
Route::get('/outcomes/deletemodal/{id}', 'InvOutcomesController@modalDelete')->name('outcomes.deletemodal');
Route::delete('/outcomes/delete/{id}', 'InvOutcomesController@destroy')->name('outcomes.destroy');
Route::get('/outcomes/statemodal/{id}', 'InvOutcomesController@modalState')->name('outcomes.statemodal');
Route::post('/outcomes/state/{id}', 'InvOutcomesController@updateState')->name('outcomes.state');
Route::get('/outcomes/items/modal/', 'InvOutcomesDetailsController@modalItems')->name('items.outcomes.modalitems');
Route::post('/items/outcomes/table', 'InvOutcomesDetailsController@tableItemsEdit')->name('items.outcomes.table');

Route::post('/outcomes/details/store/{id}', 'InvOutcomesDetailsController@storeDetails')->name('outcomes.details.store');
Route::get('/outcomes/location/{id}', 'InvOutcomesDetailsController@locationsList')->name('outcomes.locations.list'); // X EDITABLE LIST AJAX
Route::post('/outcomes/detail_location/update', 'InvOutcomesDetailsController@updateLocation')->name('outcomes.locationupdate'); // UPDATE XEDITABLE
Route::get('/outcomes/details/editmodal/{id}', 'InvOutcomesDetailsController@modalEdit')->name('outcomes.details.editmodal');
Route::post('/outcomes/details/update/{id}', 'InvOutcomesDetailsController@updateDetails')->name('outcomes.details.update');
Route::get('/outcomes/details/deletemodal/{id}', 'InvOutcomesDetailsController@modalDelete')->name('outcomes.details.deletemodal');
Route::delete('/outcomes/details/destroy/{id}', 'InvOutcomesDetailsController@destroyDetails')->name('outcomes.details.destroy');
Route::get('/outcomes/details/order', 'InvOutcomesDetailsController@order')->name('outcomes.details.order');

Route::get('/createClientsX', function () {
    abort(403);
    $direcciones = array("Calle de las americas #123","Avenida humboldt #456","Pasaje de las Flores #789","Fundacion arcoiris obrajes #1011","Plaza de los alamos #1314","Calle montenegro Av 21 #1516","Avenida de los leones #1718","Pasaje Marina Nuñez del Prado #1920","Camino de aranjuez #2122","Calle 35 de cota cota #2324","Avenida Acha #2526","Pasaje ortega #2728","Calle Jaen #2930","Avenida Zabaleta #3132","Plaza de la loba #3334","Av Busch #3536","Calle Sagarnaga #3738","Avenida 20 de octubre #3940","Mallasa calle 5 #4142","Plaza de san miguel calle 21 #4344");

    $caracteristicas_clientes = array("Tecnología de la Información","Industria Farmacéutica","Sector Automotriz","Turismo y Hospitalidad","Energías Renovables","Alimentos y Bebidas","Servicios Financieros","Construcción y Desarrollo","Educación y Formación","Medios de Comunicación","Agricultura y Agroindustria","Transporte y Logística","Entretenimiento y Eventos","Salud y Bienestar","Moda y Belleza","Gobierno y Administración Pública","Investigación y Desarrollo","Arte y Cultura","Telecomunicaciones","Consultoría Empresarial");

    $nombres_contacto = array("Juan Pérez","María Rodríguez","Carlos García","Ana Martínez","Luis López","Laura Hernández","Diego González","Sofía Díaz","Pedro Sánchez","Elena Ramírez","Miguel Gómez","Lucía Torres","Alejandro Vásquez","Paula Castro","José Morales","Valentina Álvarez","Javier Ruiz","Camila Herrera","Fernando Ortiz","Isabel Cruz");

    $numeros_celular_bolivia = array("61234567","72345678","63456789","74567890","65678901","76789012","67890123","78901234","69012345","70123456","61234567","72345678","63456789","74567890","65678901","76789012","67890123","78901234","69012345","70123456");

    $cargos_empresa = array("Gerente de Ventas","Analista de Marketing","Jefe de Recursos Humanos","Desarrollador de Software","Director Financiero","Especialista en Soporte Técnico","Coordinador de Proyectos","Ejecutivo de Cuentas","Diseñador Gráfico","Técnico de Mantenimiento","Consultor de Negocios","Analista de Datos","Ingeniero de Producción","Asistente Administrativo","Especialista en Logística","Asesor Legal","Arquitecto de Sistemas","Investigador de Mercado","Analista de Calidad","Coordinador de Eventos");

    $nits = array("45747856","987654321","876543212","234567890","98765432","1234567890","34567890","876543210","9876543","2345678901","121456789","9876543210","3456789","876547561","127656789","9876543242","345678901","87654321","2345678920","8775454");

    $tipos = ['I', 'F', 'D'];

    $clientes = ['ADMINISTRADORA DE TARJETAS DE CREDITO S.A.','AMERICAN EMBASSY LA PAZ','BANCO FIE S.A.','BANCO NACIONAL DE BOLIVIA S.A.','BISA SEGUROS Y REASEGUROS S.A.','COBEE S.A.','CRECER IFD','DELEGACION DE LA UNION EUROPEA EN BOLIVIA','ENDE TECNOLOGIAS S.A.','ENTEL S.A.','LA PRIMERA E.F.V.','NUEVATEL PCS DE BOLIVIA S.A.','RUAT','TELEFONICA CELULAR DE BOLIVIA S.A.','YPFB REFINACIÓN S. A.','Multitarea','PCP - PC PETROLERA','AMPER SRL','COSIN LTDA','PROSALUD'];


    for ($i=0; $i <20 ; $i++) {
        $nombreContacto = $nombres_contacto[$i];
        $emailContacto = generarCorreoGmail($nombreContacto);
        $client = new \App\Clients();
        $client->nombre = $clientes[$i];
        $client->nit = $nits[$i];
        $client->tipo = $tipos[array_rand($tipos)];
        $client->caracteristicas = $caracteristicas_clientes[$i];
        $client->direccion = $direcciones[$i];
        $client->nombre_contacto = $nombreContacto;
        $client->cargo_contacto = $cargos_empresa[$i];
        $client->celular_contacto = $numeros_celular_bolivia[$i];
        $client->email_contacto = $emailContacto;
        $client->estado = 1;
        $client->save();
    }
    dd("FIdwqqwdN");
});

Route::get('/createAssetsX', function () {
    abort(403);
    $assets = ['UPS ATP 2 KVA NEOMEDIC','UPS Atlantic Power de 10KVA (reenvio stock SANTA CRUZ)','BANCO DE CAPACITORES 125KVar 2','Aire Stulz CPS','Equipo Inversor Monofasico','G.E. GRUPEL 22KVA SAN RAMON','AIRE DE PRECISION  STULZ N°5 SALA SWITCH','SUCRE RIELTEC SOCOMEC  DELPHYS MX 250KVA','VISION.TEMBLDRANI;ATP;9103-83928KG1;3KVA','HANSA.SM;(210403-98600017);ATP;G2T;2KVA','LPZ.CALACOTO;ATP G2 T ;2KVA','México ATP 9103-73566-00P (1)','AAP N° 1 SALA NUEVA CASITA','A.SENKATA;(G42K24011);EATON;9E 10Ki;10KVA','COROICO (G.E. 11KVA)','GRUPO ELECTROGENO','S.C. RADAR CARMEN SCMC DELPHYS MP ELITE 100KVA','SHOWROOM (210403-98600013) ATP G2T 2KVA (1)','BBVA.SCZ;P1;ATP;INNOVAG.2.RACK/TOWER;10KVA','AAP N°1 RADIO BASE TACOPAYA'];

    $direcciones = array("Calle de las americas #123","Avenida humboldt #456","Pasaje de las Flores #789","Fundacion arcoiris obrajes #1011","Plaza de los alamos #1314","Calle montenegro Av 21 #1516","Avenida de los leones #1718","Pasaje Marina Nuñez del Prado #1920","Camino de aranjuez #2122","Calle 35 de cota cota #2324","Avenida Acha #2526","Pasaje ortega #2728","Calle Jaen #2930","Avenida Zabaleta #3132","Plaza de la loba #3334","Av Busch #3536","Calle Sagarnaga #3738","Avenida 20 de octubre #3940","Mallasa calle 5 #4142","Plaza de san miguel calle 21 #4344");

    $clientIds = ['33','1','28','37','26','32','41','35','29','39','23','2','40','30','25','36','42','31','27','24','34','38'];

    $ciudades = [0,1,2,3,4,5,6,7,8];
    $categorias = [0,1,2,3,4,5,6,7,8,9,10];

    $numeros_serie_equipos = array("ABCD1234EFGH5678","WXYZ9876UVTR3210","JKLM3456QRST7890","1234EFGH5678IJKL","UVTR3210WXYZ987621","QRST7890JK3LM3456","EFGH56784IJKL1234","UVTR321DW0WXYZ9876","QRST7890JKL4M3456","IJKL12334EFGH5678","UVTR321340WXYZ9876","QRST7890JKLM34C56","EFGH5678IJKL61234","UVTR32510WXYZ9876","QRST7890J53KLM3456","IJKL1234EFGH5678","UVTR3210WXYZ49876","QRST7890JKLM3456","EFGH5678IJK6L1234","UVTR32130WXYZ9876");

    $marcas_equipos = array("Samsung","LG","Panasonic","Sony","Toshiba","Sharp","Philips","Hitachi","Mitsubishi Electric","Carrier","Frigidaire","Daikin","General Electric","Haier","Hisense","Lennox","Midea","Pioneer","Trane","Whirlpool");

    $modelos_equipos = ['NEOMEDIC','Atlantic Power','KVAR2','CPS-2','Powerflow1','GRUPEL','SWITCH-111','RIELTEC','VISION','G2t','ATP G2 T','9103-73566-00P','AAP N° 1','G42K24011','Ecochill 4','Inverter 2','SCMC ELITE 100KVA','ATP G2T ','INNOVAG.2.','AAP new 2'];

    for ($i=0; $i < 20; $i++) {
        // Guardar los campos de activo
        $registroMaximo = \App\StAssets::select('cod')->where('cod', 'LIKE', "%AS%")->max('cod');
        $cod = generateCode($registroMaximo,'AS000001','AS',2,6);

        $asset = new \App\StAssets();
        $asset->cod = $cod;
        $asset->client_id = $clientIds[array_rand($clientIds)];
        $asset->nombre = $assets[$i];
        $asset->categoria = $categorias[array_rand($categorias)];
        $asset->ubicacion = $direcciones[$i];;
        $asset->ciudad = $ciudades[array_rand($ciudades)];
        $asset->nro_serie = $numeros_serie_equipos[$i];
        $asset->marca = $marcas_equipos[$i];
        $asset->modelo = $modelos_equipos[$i];
        $asset->capacidad = rand(100, 999);
        $asset->estado = 1;
        $asset->save();
    }

    dd("GUARDADO");
});


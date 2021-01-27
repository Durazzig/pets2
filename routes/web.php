<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/proveedores', 'ProviderController@index')->name('providers.index')->middleware('auth','role:admin cajero almacenista');
Route::get('/providers/new', 'ProviderController@create')->name('providers.create')->middleware('auth','role:admin cajero almacenista');
Route::post('/providers', 'ProviderController@store')->name('providers.store')->middleware('auth','role:admin cajero almacenista');
Route::any('/providers/edit/{id}','ProviderController@edit')->name('providers.edit')->middleware('auth','role:admin');
Route::any('/providers/update/{id}', 'ProviderController@update')->name('providers.update')->middleware('auth','role:admin');
Route::delete('/providers/delete/{id}', 'ProviderController@destroy')->name('providers.delete')->middleware('auth','role:admin');

Route::get('/facturas', 'BillController@index')->name('bills.index')->middleware('auth','role:admin cajero almacenista');
Route::get('/facturas/new', 'BillController@create')->name('bills.create')->middleware('auth','role:admin cajero');
Route::post('/facturas/store', 'BillController@store')->name('bills.store')->middleware('auth','role:admin cajero');
Route::any('/facturas/filterByDate', 'BillController@filterDate')->name('bills.filterDate')->middleware('auth','role:admin cajero almacenista');
Route::any('/facturas/edit/{id}', 'BillController@edit')->name('bills.edit')->middleware('auth','role:admin');
Route::any('facturas/update/{id}', 'BillController@update')->name('bills.update')->middleware('auth','role:admin');
Route::post('store_image/insert_image','BillController@store');
Route::get('store_image/fetch_image/{id}','BillController@fetch_image');
Route::delete('/facturas/delete/{id}', 'BillController@destroy')->name('bills.destroy')->middleware('auth','role:admin');

Route::get('/consultas', 'ConsultaController@index')->name('consultas.index')->middleware('auth','role:admin cajero medico_consulta recepcionista');
Route::get('/consultas/new', 'ConsultaController@create')->name('consultas.create')->middleware('auth','role:admin cajero recepcionista medico_consulta');
Route::any('/consultas/store', 'ConsultaController@store')->name('consultas.store')->middleware('auth','role:admin cajero recepcionista medico_consulta');
Route::any('/consultas/edit/{id}', 'ConsultaController@edit')->name('consultas.edit')->middleware('auth','role:admin cajero medico_consulta');
Route::any('/consultas/update/{id}', 'ConsultaController@update')->name('consultas.update')->middleware('auth','role:admin cajero medico_consulta');
Route::any('/consultas/filterByDate', 'ConsultaController@filterDate')->name('consultas.filterDate')->middleware('auth','role:admin cajero medico_consulta recepcionista');
Route::delete('/consultas/delete/{id}', 'ConsultaController@destroy')->name('consultas.delete')->middleware('auth','role:admin cajero');

Route::get('/citas', 'CitaController@index')->name('citas.index')->middleware('auth');
Route::get('/citas/new', 'CitaController@create')->name('citas.create')->middleware('auth');

Route::get('/owners', 'OwnerController@index')->name('owners.index')->middleware('auth','role:admin cajero medico_consulta apoyo_medico recepcionista');
Route::get('/owners/new', 'OwnerController@create')->name('owners.create')->middleware('auth','role:admin recepcionista cajero medico_consulta');
Route::any('/owners/store', 'OwnerController@store')->name('owners.store')->middleware('auth','role:admin recepcionista cajero medico_consulta');
Route::any('/owners/pets/{id}', 'OwnerController@pets')->name('owners.pets')->middleware('auth','role:admin recepcionista cajero medico_consulta');
Route::any('/owners/pets/addPet/{id}', 'OwnerController@addOwnerPet')->name('owners.addPet')->middleware('auth','role:admin recepcionista cajero medico_consulta');
Route::delete('/owners/delete/{id}', 'OwnerController@destroy')->name('owners.delete')->middleware('auth','role:admin');

Route::get('/permisos', 'PermisoController@index')->name('permisos.index')->middleware('auth','role:admin cajero almacenista hostess apoyo_medico recepcionista estetica medico_consulta mantenimiento');
Route::get('/permisos/new', 'PermisoController@create')->name('permisos.create')->middleware('auth','role:admin cajero almacenista hostess apoyo_medico recepcionista estetica medico_consulta mantenimiento');
Route::any('/permisos/store', 'PermisoController@store')->name('permisos.store')->middleware('auth','role:admin cajero almacenista hostess apoyo_medico recepcionista estetica medico_consulta mantenimiento');
Route::any('/permisos/edit/{id}', 'PermisoController@edit')->name('permisos.edit')->middleware('auth','role:admin');
Route::any('/permisos/update{id}', 'PermisoController@update')->name('permisos.update')->middleware('auth','role:admin');
Route::delete('/permisos/delete/{id}', 'PermisoController@destroy')->name('permisos.delete')->middleware('auth','role:admin');

Route::get('/pets', 'PetController@index')->name('pets.index')->middleware('auth');
Route::get('/pets/new', 'PetController@create')->name('pets.create')->middleware('auth');
Route::any('/pets/store', 'PetController@store')->name('pets.store')->middleware('auth');
Route::any('/pets/storeFromOwner/{id}', 'PetController@storeFromOwner')->name('pets.storeFromOwner')->middleware('auth');
Route::any('/pets/edit/{id}', 'PetController@editFromOwner')->name('pets.editFromOwner')->middleware('auth');
Route::any('/pets/update/{id}', 'PetController@updateFromOwner')->name('pets.updateFromOwner')->middleware('auth');
Route::delete('/pets/delete/{id}', 'PetController@destroy')->name('pets.delete')->middleware('auth','role:admin');

Route::get('/empleados', 'EmpleadoController@index')->name('empleados.index')->middleware('auth','role:admin');
Route::get('/empleados/new', 'EmpleadoController@create')->name('empleados.create')->middleware('auth','role:admin');
Route::any('/empleados/store', 'EmpleadoController@store')->name('empleados.store')->middleware('auth','role:admin');
Route::any('/empleados/edit/{id}', 'EmpleadoController@edit')->name('empleados.edit')->middleware('auth','role:admin');
Route::any('/empleados/get', 'EmpleadoController@getEmpleados')->name('empleados.getEmpleados')->middleware('auth','role:admin');
Route::any('/empleados/update{id}', 'EmpleadoController@update')->name('empleados.update')->middleware('auth','role:admin');
Route::delete('/empleados/delete/{id}', 'EmpleadoController@destroy')->name('empleados.delete')->middleware('auth','role:admin');

Route::get('/createWord/{id}', ['as'=>'createWord', 'uses'=>'document@createWordDoc'])->middleware('auth');

Route::get('/error', 'HomeController@error')->name('error')->middleware('auth');

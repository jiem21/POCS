<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'machinesController@index');
Route::get('/List-Of-All-Machines', 'machinesController@list_machines')->name('listmachine');
Route::post('/save-machines', 'machinesController@save_machine')->name('save_machine');
Route::post('/update-machines', 'machinesController@update_machine')->name('update_machine');
Route::get('/get-details', 'machinesController@get_detials')->name('get_detials');
Route::get('/load-data', 'machinesController@Load_data')->name('load');
Route::get('/qet-data', 'machinesController@qet')->name('qet-load');
Route::any('/update-machine', 'machinesController@update')->name('update');
// Per Machine
Route::any('/Machine', 'machinesController@update')->name('update');
Route::any('/Machine-Setup', 'machinesController@machine_setup')->name('machine_setup');
Route::any('/Machine-Order', 'machinesController@machine_order')->name('machine_order');
Route::any('/Activity-View', 'machinesController@activity_logs_view')->name('activity_logs');

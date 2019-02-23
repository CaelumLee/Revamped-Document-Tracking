<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/accepted', 'HomeController@accepted')->name('accepted');
Route::get('/inactive', 'HomeController@inactive')->name('inactive');
Route::get('/received', 'HomeController@received')->name('received');
Route::get('/archived', 'HomeController@archived')->name('archived');

Route::get('/batch', 'BatchController@index')->name('batch');

Route::post('/addressAjax', 'HomeController@getAddress');
Route::post('/userlist', 'HomeController@getUsers');

Route::resource('docu', 'DocuController');
Route::post('/receive', 'TransactionsController@receive_docu');
Route::post('/send', 'TransactionsController@send_docu');

Route::post('/upload', 'FileUploadsController@upload');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/notifications', 'NotificationController@createNewDocuNotification');
    Route::get('/readAll', 'NotificationController@readAllNotifications')->name('readAll');
});
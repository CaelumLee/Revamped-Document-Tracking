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
})->name('start');

Auth::routes();

Route::post('/first', 'FirstLoginController@index')->name('first');
Route::get('/forgot', 'Auth\ForgotPasswordController@index')->name('forgot');
Route::post('/findUser', 'Auth\ForgotPasswordController@findUser')->name('findUser');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/accepted', 'HomeController@accepted')->name('accepted');
Route::get('/inactive', 'HomeController@inactive')->name('inactive');
Route::get('/received', 'HomeController@received')->name('received');
Route::get('/archived', 'HomeController@archived')->name('archived');

Route::get('/batch', 'BatchController@index')->name('batch');
Route::post('/add', 'BatchController@add');

Route::post('/addressAjax', 'HomeController@getAddress')->name('ajax_address');
Route::post('/userlist', 'HomeController@getUsers')->name('ajax_userlists');

Route::resource('docu', 'DocuController');
Route::post('/restore/{id}', 'DocuController@restore');
Route::post('/approve/{id}', 'DocuController@approve');
Route::post('/receive', 'TransactionsController@receive_docu');
Route::post('/send', 'TransactionsController@send_docu');
Route::post('/deadline', 'TransactionsController@update_date_deadline');

Route::get('/dynamic_pdf/pdf/{id}', 'DynamicPDFController@pdf')->name('pdf');

Route::get('/responses/{id}', 'TransactionsController@responses')->name('responses');
Route::get('/routeinfo/{id}', 'TransactionsController@routeinfo')->name('route_info');

Route::post('/upload', 'FileUploadsController@upload');
Route::post('/jsonFile', 'FileUploadsController@getFiles')->name('file_to_json');

Route::get('/dashboard/statistics','AdminDashboard@index')->name('dashboard');

Route::get('/dashboard/departments', 'DepartmentDashboardController@index')->name('department');
Route::post('/dashboard/departments/add','DepartmentDashboardController@add');
Route::post('/dashboard/departments/edit','DepartmentDashboardController@edit');
Route::post('/dashboard/departments/disable','DepartmentDashboardController@disable');

Route::get('/dashboard/holidays','HolidaysDashboardController@index')->name('holidays');
Route::post('/dashboard/holidays/disable','HolidaysDashboardController@disable');

Route::get('/dashboard/docutype','DocuTypeDashboardController@index')->name('docuType');
Route::post('/dashboard/docutype/add','DocuTypeDashboardController@add');
Route::post('/dashboard/docutype/edit','DocuTypeDashboardController@edit');
Route::post('/dashboard/docutype/disable','DocuTypeDashboardController@disable');

Route::get('/dashboard/users','UserController@index')->name('userlists');
Route::get('/dashboard/allusers','UserController@allUsers')->name('allUsers');
Route::post('/dashboard/users/add','UserController@add');
Route::post('/dashboard/users/edit','UserController@edit');
Route::post('/dashboard/users/pass','UserController@pass');
Route::post('/dashboard/users/disable','UserController@disable');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/notifications', 'NotificationController@createNewDocuNotification')->name('notifs');
    Route::get('/readAll', 'NotificationController@readAllNotifications')->name('readAll');
});
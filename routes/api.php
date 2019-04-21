<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/docuapi', 'DocuAPI@index')->name('DocuJson');
Route::get('/deptlist', 'DocuAPI@deptlist')->name('DeptList');

Route::post('/mobile_login', 'MobileAPI@login');
Route::get('/all_docus', 'MobileAPI@all_docu');
Route::get('/my_docus/{id}', 'MobileAPI@my_docu');
Route::get('/accept', 'MobileAPI@accepted');
Route::get('/inactive', 'MobileAPI@inactive');
Route::get('/archive', 'MobileAPI@archived');
Route::get('/receive/{id}', 'MobileAPI@received');

Route::post('/show', 'MobileAPI@show');

Route::post('/qr_details', 'MobileAPI@details');
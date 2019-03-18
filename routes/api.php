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
Route::post('/my_docus', 'MobileAPI@my_docu');
Route::get('/archived', 'MobileAPI@accepted');
Route::get('/inactive', 'MobileAPI@inactive');
Route::post('/received', 'MobileAPI@received');
Route::get('/archived', 'MobileAPI@archived');

Route::post('/show', 'MobileAPI@show');
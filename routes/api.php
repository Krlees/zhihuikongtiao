<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Api" middleware group. Enjoy building your API!
|
*/

// url默认带上Api标示

// Route::group(['namespace' => 'Api','middleware' => ['wechat.oauth']], function () {
//     Route::any('order-create','OrderController@create');
// });

Route::get('get-district/{upid?}','Api\BaseController@getDistrict');



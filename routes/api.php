<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['api']], function () {
    Route::group(['namespace' => 'Backend'], __DIR__ . '/backend/api.php');  /*后台功能*/
    Route::group(['namespace' => 'Frontend'], __DIR__ . '/frontend/api.php'); /*前台功能*/
});
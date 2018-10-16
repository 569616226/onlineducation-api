<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Frontend'], __DIR__ . '/frontend/web.php');
Route::group(['namespace' => 'Backend'], __DIR__ . '/backend/web.php');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


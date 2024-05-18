<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => '\Modules\User\src\Http\Controllers'], function () {
    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::prefix('/users')->name('users.')->middleware('web')->group(function () {
            Route::get('/', 'UserController@index')->name('index');
            Route::get('/detail/{id}', 'UserController@detail');
            Route::get('/create', 'UserController@add')->name('create');
            Route::post('/create', 'UserController@store')->name('store');

            Route::get('/edit/{user}', 'UserController@edit')->name('edit');
            Route::post('/edit/{user}', 'UserController@update')->name('update');

            Route::get('data','UserController@data')->name('data');
            Route::delete('delete/{id}','UserController@delete')->name('delete');
        });
    });
});

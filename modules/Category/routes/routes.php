<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => '\Modules\Category\src\Http\Controllers'], function () {
    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::prefix('/categories')->name('categories.')->middleware('web')->group(function () {
            Route::get('/', 'CategoryController@index')->name('index');
            Route::get('/detail/{id}', 'CategoryController@detail');
            Route::get('/create', 'CategoryController@add')->name('create');
            Route::post('/create', 'CategoryController@store')->name('store');

            Route::get('/edit/{category}', 'CategoryController@edit')->name('edit');
            Route::post('/edit/{category}', 'CategoryController@update')->name('update');

            Route::get('data','CategoryController@data')->name('data');
            Route::delete('delete/{id}','CategoryController@delete')->name('delete');
        });
    });
});

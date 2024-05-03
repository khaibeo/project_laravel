<?php 
use Illuminate\Support\Facades\Route;

// Route::middleware('demo')->get('/user',function(){
//     return config('test.test');
// });

Route::group(['namespace' => '\Modules\User\src\Http\Controllers'],function(){
    Route::prefix('/users')->group(function(){
        Route::get('/','UserController@index');
        Route::get('/detail/{id}','UserController@detail');
    });
});
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    // 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function(){

    Route::controller(AuthController::class)->prefix('auth')->group(function(){
        Route::post('register','register');
        Route::post('login','login');
    });



    Route::controller(CategoryController::class)->prefix('/category')->middleware('auth')->group(function(){

        Route::post('store','store');
        Route::get('/','index');

    });
});



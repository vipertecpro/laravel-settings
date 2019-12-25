<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => Config::get('settings.middleware')], static function () {
    Route::group([
        'prefix' => Config::get('settings.route_prefix'),
        'as'     => Config::get('settings.route-as'),
    ], static function () {
        Route::get('/','Vipertecpro\Settings\App\Http\Controllers\SettingsController@index')->name('list');
        Route::get('/create/{type}','Vipertecpro\Settings\App\Http\Controllers\SettingsController@create')->name('create');
        Route::post('/store','Vipertecpro\Settings\App\Http\Controllers\SettingsController@store')->name('store');
        Route::get('/edit/{settings_id}','Vipertecpro\Settings\App\Http\Controllers\SettingsController@edit')->name('edit');
        Route::patch('/update/{settings_id}','Vipertecpro\Settings\App\Http\Controllers\SettingsController@update')->name('update');
        Route::post('/remove/{settings_id}','Vipertecpro\Settings\App\Http\Controllers\SettingsController@destroy')->name('remove');
        Route::get('/download/{setting}', 'Vipertecpro\Settings\App\Http\Controllers\SettingsController@fileDownload');
    });
});

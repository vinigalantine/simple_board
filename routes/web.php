<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('posts');	
});

// Route::resource('posts', 'PostController');

Route::prefix('posts')->group(function(){
	Route::get('/', 'PostController@index')->name('interests.index');
	Route::post('/', 'PostController@store')->name('interests.store');
	Route::put('/{id}', 'PostController@update')->name('interests.more_info');
	Route::delete('/{id}', 'PostController@destroy')->name('interests.destroy');
});
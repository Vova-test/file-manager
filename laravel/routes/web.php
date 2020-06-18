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

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

	Route::get('/', function () {
        return redirect('/home');
    });

	Route::get('/home/{parent?}', 'HomeController@index')->name('home');
	Route::post('/file/vote', 'HomeController@addVote');
	Route::post('/file/upload', 'HomeController@uploadFile')->name('file.upload');
	Route::post('/folder/add', 'HomeController@addFolder')->name('folder.add');
});

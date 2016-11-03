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
    return view('welcome');
});
Route::get('/logout', 'Auth\LoginController@logout')->middleware('web');
Route::any('/clef/logout', 'ClefController@logout');
Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function() {
  Route::get("/multiauth", "ClefController@index");
  Route::get("/clef", "ClefController@login");
});
Route::group(['middleware' => ['auth', 'clef']], function() {
  Route::get('/dashboard', function() {
    return view('dashboard');
  });
});

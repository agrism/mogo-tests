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

Route::get('/', 'TournamentController@index')->name('home');
Route::resource('tournaments', 'TournamentController');
Route::resource('groups', 'GroupController');
Route::get('groups/{id}/generate-results', 'GroupController@generateResults')->name('groups.generate-results');
Route::get('generatePlayoff/{id}', 'TournamentController@generatePlayoff')->name('generatePlayoff');



Route::resource('teams', 'TeamController');




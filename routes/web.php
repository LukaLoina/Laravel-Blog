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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/create', 'PostsController@createForm')->name('createForm');
Route::post('/create', 'PostsController@create')->name('create');
Route::get('/read/{id}', 'PostsController@read')->name('read');
Route::get('update/{id}', 'PostsController@updateForm')->name('updateForm');
Route::post('update/{id}', 'PostsController@update')->name('update');
Route::get('delete/{id}', 'PostsController@deleteForm')->name('deleteForm');
Route::post('delete/{id}', 'PostsController@delete')->name('delete');

Route::post('comment/{id}', 'CommentsController@comment')->name('comment');

Route::post('like/{id}', 'LikesController@like')->name('like');
Route::post('unlike/{id}', 'LikesController@unlike')->name('unlike');

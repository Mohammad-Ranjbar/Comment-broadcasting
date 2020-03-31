<?php

use App\User;

Route::get('/', function () {
    return view('home');
});
Route::get('/sidebar', function () {
    return view('sidebar');
});
Route::post('/form','FormController@store')->name('form');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users/search', 'UserController@search');

Route::resource('/posts', 'PostController');
Route::post('/posts/{post}/comments', 'CommentController@store');


Route::post('/reply/{comment}', 'CommentController@reply');


Route::delete('/posts/comment/{id}','CommentController@delete');

Route::patch('/posts/comments/{id}','CommentController@update');

<?php

use Illuminate\Http\Request;

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details', 'UserController@details');
});

Route::get('/posts/{post}/comments', 'CommentController@index');
Route::middleware('auth:api')->group(function () {
    Route::post('/posts/{post}/comments', 'CommentController@store');
});

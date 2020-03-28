<?php

use Illuminate\Support\Facades\Route;

//인덱스
Route::get('/', 'exampleController@index');
// Ajax 연습페이지
Route::get('/example', 'exampleController@example');
Route::get('/example/{id}/{ee}', 'exampleController@ix');

// 관리자 로그인
Route::middleware(['cors'])->group(function(){
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::post('login','AdminController@login');
});

//글 관련 Route
Route::resource('posts', 'PostsController',['except' => ['edit','index']])->middleware('cors');

//글 상세 내용페이지
Route::get('posts/{id}/{iaa}', 'PostsController@post_list')->middleware('cors');


// 댓글 관련 Route
Route::resource('reply', 'ReplysController')->middleware('cors');




<?php

use Illuminate\Support\Facades\Route;

//인덱스
Route::get('/', 'exampleController@index');
// Ajax 연습페이지
Route::get('/example', 'exampleController@example');
Route::post('/idstore', 'exampleController@store');
Route::get('/page', 'exampleController@page');

// 관리자 로그인
Route::middleware(['cors'])->group(function(){
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::post('login','AdminController@login');
});

//글 관련 Route
Route::resource('posts', 'PostsController',['except' => ['index','create']])->middleware('cors');

//글작성 이미지저장
Route::post('posts/image', 'PostsController@imageStore')->middleware('cors');

//글 상세 내용페이지
Route::get('posts/{category}/{num}', 'PostsController@content')->middleware('cors');

// 댓글 관련 Route
Route::resource('reply', 'ReplysController')->middleware('cors');




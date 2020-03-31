<?php

use Illuminate\Support\Facades\Route;

//인덱스
Route::get('/', 'exampleController@index');
// Ajax 연습페이지
Route::get('/example', 'exampleController@example');
Route::post('/idstore', 'exampleController@store');

// 관리자 로그인
Route::middleware(['cors'])->group(function(){
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::post('login','AdminController@login');
});


Route::middleware(['cors'])->group(function(){
    //글 관련 Route
    Route::resource('posts', 'PostsController',['except' => ['index','create']]);

    //글작성 이미지저장
    Route::post('posts/image', 'PostsController@imageStore');

    //카테고리별 글리스트
    Route::get('posts/{category}/{num}', 'PostsController@category_list');

    //글 조회수 +1 증가(작업 아직안함)
    Route::get('posts/{num}', 'PostsController@viewUp');
});

// 댓글 관련 Route
Route::resource('reply', 'ReplysController')->middleware('cors');




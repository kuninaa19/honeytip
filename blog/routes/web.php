<?php

use Illuminate\Support\Facades\Route;

//auth미들웨어를 사용해야 로그인
Route::middleware(['auth'])->group(function() {

//    Route::get('/', 'Controller@index');
    //소셜로그아웃기능s
    Route::get('/hi', 'exampleController@store');

    Route::post('/example', 'exampleController@store');
});

Route::get('/', 'Controller@index');

//소셜 로그인
Route::get('/login', function () {
    return view('auth/login');
})->name('login');

// 구글 소셜로그인
Route::get('auth/social/{social}', 'Auth\LoginController@redirectToProvider');
Route::get('/{social}/callback', 'Auth\LoginController@handleProviderCallback');

//관리자로그인 관리자 아이디 생성
Route::middleware(['cors'])->group(function(){
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::post('make_id','Auth\AdminController@store_secret');
    Route::post('login','Auth\AdminController@login');
});

//회원 관련 Route
    Route::resource('user', 'UserInfoController');

//글 관련 Route
    Route::resource('posts', 'Article\PostsController',['except' => ['create']]);
    //관리자페이지 전체 글 내용 보여주기
    Route::get('posts/{category}/all', 'Article\PostsController@post_list');
    //글작성 이미지저장
    Route::post('posts/image', 'Article\PostsController@image_store');
    //카테고리별 글리스트
    Route::get('posts/{category}/{num}', 'Article\PostsController@category_list');
    //글 조회수 +1 증가(작업 아직안함)
    Route::get('posts/{num}', 'Article\PostsController@like_count');


// 댓글 관련 Route
    Route::resource('comments', 'Article\CommentsController',['except' => ['index','create','show']]);
    Route::get('comments/{postNum}/{page}', 'Article\CommentsController@comments_list');

// 대댓글 관련 Route
    Route::resource('reply', 'Article\ReplyController',['except' => ['index','create','show']]);

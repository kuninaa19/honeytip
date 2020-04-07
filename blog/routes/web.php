<?php

use Illuminate\Support\Facades\Route;

//Route::get('api/user', function () {
//    // Only authenticated users may enter...
//})->middleware('auth.basic');

//메인페이지
//auth미들웨어를 사용해야 로그인
Route::middleware(['auth'])->group(function() {

    Route::get('/', 'Controller@index');
});
//소셜로그아웃기능
Route::get('/hi', 'exampleController@store');

//소셜 로그인
Route::get('/login', function () {
    return view('/auth/login');
})->name('login');

Route::get('auth/social/google', 'Auth\LoginController@redirectToProvider');
Route::get('/google/callback', 'Auth\LoginController@handleProviderCallback');


//관리자로그인 관리자 아이디 생성
Route::middleware(['cors'])->group(function(){
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::post('make_id','Auth\AdminController@storeSecret');
    Route::post('login','Auth\AdminController@login');
});


Route::middleware(['cors'])->group(function(){
    //글 관련 Route
    Route::resource('posts', 'Article\PostsController',['except' => ['create']]);

    //글작성 이미지저장
    Route::post('posts/image', 'Article\PostsController@imageStore');

    //카테고리별 글리스트
    Route::get('posts/{category}/{num}', 'Article\PostsController@category_list');

    //글 조회수 +1 증가(작업 아직안함)
    Route::get('posts/{num}', 'Article\PostsController@viewUp');
});

Route::middleware(['cors'])->group(function(){
    // 댓글 관련 Route
    Route::resource('comments', 'Article\CommentsController',['except' => ['index','create','show']]);

    Route::get('comments/{postNum}/{page}', 'Article\CommentsController@comments_list');

});

Route::middleware(['cors','auth'])->group(function(){
    // 대댓글 관련 Route
    Route::resource('reply', 'Article\ReplyController',['except' => ['index','create']]);

    Route::get('reply/{category}/{num}', 'Article\ReplyController@kae');
});

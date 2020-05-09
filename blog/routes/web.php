<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Controller@index');

////관리자로그인 관리자 아이디 생성
//Route::middleware(['cors'])->group(function(){
//    Route::get('/csrf_token', function(){
//        return csrf_token();
//    });
//    Route::post('make_id','Auth\AdminController@store_secret');
//    Route::post('login','Auth\AdminController@login');
//});

// 소셜로그인
Route::get('auth/social/{social}', 'Auth\LoginController@redirectToProvider');
Route::get('/{social}/callback', 'Auth\LoginController@handleProviderCallback');


//회원 관련 Route
    Route::resource('user', 'UserInfoController',['except' => ['index','create','store','edit','update']]);
    // 유저 댓글 리스트(페이징)
    Route::get('user/{id}/comments/list/{num}', 'UserInfoController@comments_list');
    // 유저 좋아요 리스트(페이징)
    Route::get('user/{id}/like/list/{num}', 'UserInfoController@like_list');

//글 관련 Route
    Route::resource('posts', 'Article\PostsController',['except' => ['create']]);
    //관리자페이지 전체 글 내용 보여주기
    Route::get('posts/{category}/all', 'Article\PostsController@post_list');
    //글작성 이미지저장
    Route::post('posts/image', 'Article\PostsController@image_store');
    //카테고리별 글리스트
    Route::get('posts/{category}/{num}', 'Article\PostsController@category_list');
    //글 상세페이지 글 추천 리스트
    Route::get('posts/recommendation/list/{num}', 'Article\PostsController@recommend_list');
    // 댓글 개수 확인
    Route::get('posts/comments/{postNum}/count', 'Article\PostsController@comments_count');

//좋아요 관련 Route
    Route::resource('like', 'Article\LikeController',['except' => ['index','create','show','update','destroy']]);
    //유저가 보고있는 글에 유저 좋아요를 했는지 안했는지 확인
    Route::get('like/{postNum}/{id}', 'Article\LikeController@like_check');

// 댓글 관련 Route
    Route::resource('comments', 'Article\CommentsController',['except' => ['index','create']]);
    // 댓글 리스트 (페이징)
    Route::get('comments/{postNum}/{page}', 'Article\CommentsController@comments_list');


// 대댓글 관련 Route
    Route::resource('reply', 'Article\ReplyController',['except' => ['index','create','show']]);

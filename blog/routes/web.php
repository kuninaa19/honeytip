<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'userController@index');

Route::get('/index', 'userController@index');
Route::get('/example', 'userController@example');

Route::get('user', function () {
    return '일반 값전달';
});

Route::get('user/{id?}', function ($id= 'null') {
    return '리소스명칭'.$id;
});
Route::middleware(['cors'])->group(function(){
    Route::get('/csrf_token', function(){
        return csrf_token();
    });
    Route::post('login/auth','userController@login');
});


Route::post('info','userController@aa');
Route::get('exa/{info}','userController@name');

//인자 한개
//Route::get('/', function () {
//    $greeting = 'Hello';
//
//    return view('index')->with('greeting', $greeting);
//});

//Route::get('/', function () {
//    return view('index')->with([
//        'greeting' => 'Good morning ^^/',
//        'name'     => 'Appkr'
//    ]);
//});
//
//Route::get('/', function () {
//    return view('index', [
//        'greeting' => 'Ola~',
//        'name'     => 'Laravelians',
//        'items'    => ['Apple','Banana']
//    ]);
//});

//Route::get('/', function () {
//    $view = view('index');
//    $view->greeting = "Hey~ What's up";
//    $view->name = 'everyone';
//    $view->items = ['Apple','Banana'];
//    return $view;
//});

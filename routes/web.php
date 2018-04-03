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

/*Route::get('/', function () {
    //return view('welcome') -> with('name', 'Foo');
    //return '<h1>Heloo FOO</h1>';

//    $item = ['apple', 'banana', 'tomato'];
////
////    return view('welcome', ['items' => $item]);
///
    return view('welcome');
});*/

//Route::get('/', 'WelcomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('auth/login', function() {

    $credentials = [
        'email' => 'josh@gmail.com',
        'password' => 'password'
    ];

    if(! auth()->attempt($credentials)) {

        return '로그인 정보가 정확하지 않습니다.';
    }

    return redirect('protected');
});

Route::get('protected', function() {

    dump(session()->all());

    if(! auth()->check()) {

        return 'who are you?';
    }

    return 'welcome'. auth()->user()->name;
});

Route::get('auth/logout', function() {
    auth() -> logout();

    return 'see you~';
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*DB::listen(function ($query){

    var_dump($query->sql);
});*/


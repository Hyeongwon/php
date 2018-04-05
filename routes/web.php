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

Route::get('/', [
    'as' => 'root',
    'uses' => 'WelcomeController@index',
]);

Route::resource('articles', 'ArticlesController');

/* 사용자 가입 */
Route::get('auth/register', [
    'as' => 'users.create',
    'uses' => 'UsersController@create'
]);

Route::post('auth/register', [
    'as' => 'users.store',
    'uses' => 'UsersController@store'
]);

Route::get('auth/confirm/{code}', [
    'as' => 'users.confirm',
    'uses' => 'UsersController@confirm'
])->where('code', '[\pL-\pN]{60}');

/*사용자 인증*/
Route::get('auth/login', [

    'as' => 'sessions.create',
    'uses' => 'SessionsController@create'
]);

Route::post('auth/login', [

    'as' => 'sessions.store',
    'uses' => 'SessionsController@store'
]);

Route::post('auth/logout', [

    'as' => 'sessions.destroy',
    'uses' => 'SessionsController@destroy'
]);

/*비밀번호 초기화*/
Route::get('auth/remind', [

    'as' => 'remind.create',
    'uses' => 'PasswordsController@getRemind',
]);

Route::post('auth/remind', [

    'as' => 'remind.store',
    'uses' => 'PasswordsController@getRemind',
]);

Route::get('auth/reset/{token}', [

    'as' => 'reset.create',
    'uses' => 'PasswordsController@postRemind',
]);

Route::post('auth/reset', [

    'as' => 'reset.store',
    'uses' => 'PasswordsController@postReset',
]);



Route::get('protected', function() {

    dump(session()->all());

    if(! auth()->check()) {

        return 'who are you?';
    }

    return 'welcome'. auth()->user()->name;
});

Route::get('/home', 'HomeController@index')->name('home');

/*DB::listen(function ($query){

    var_dump($query->sql);
});*/

Route::get('mail', function() {

    $article = App\Article::with('user')->find(1);

    return Mail::send(
        'emails.articles.created',
        compact('article'),
        function ($message) use ($article) {

            $message->to('bhw0506@gmail.com');
            $message->subject('새 글이 등록되었습니다 -' . $article->title);
        }
    );
});

Route::get('markdown', function() {

    $text =<<<EOT
    
    # 마크다운 예제 1
    
    [마크다운][1]
    
    ## 순서 없는 목록
    
    - 첫 번째 항목
    - 두 번째 항목[^1]
    
    [1]: http://daringfireball.net/project/markdown
    
    [^1]: 두 번쨰 항목_ http://google.com

EOT;

    return app(ParsedownExtra::class)->text($text);

});

Route::get('docs/{file?}', 'DocsController@show');

Route::get('docs/images/{images}', 'DocsController@image')->
    where('image', '[\pL-\pN\._-]+-img-[0-9]{2}.png');

Route::get('tags/{slug}/articles', [

    'as' => 'tags.articles.index',
    'uses' => 'ArticlesController@index'
]);

Route::resource('comments', 'CommentsController', ['only' => ['update', 'destroy']]);
Route::resource('articles.comments', 'CommentsController', ['only' => 'store']);

Route::post('comments/{comment}/votes', [

    'as' => 'comments.vote',
    'uses' => 'CommentsController@vote',
]);


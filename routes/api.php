<?php
Route::group([
    'domain' => 'api.myapp.test',
    'namespace' => 'Api',
    'as' => 'api.',
], function () {
    /* api.v1 */
    Route::group([
        'prefix' => 'v1',
        'namespace' => 'v1',
        'as' => 'v1.',
//        라라벨 5.3부터는 app/Providers/RouteServiceProvider.php 에서 이미 api 미들웨어 그룹을 적용하고 있다.
//        그리고 api 미들웨어 그룹에는 throttle 미들웨어가 포함되어 있다. 즉, 아래 구문은 필요없다.
//        라라벨 5.2를 이용한다면 이 구문을 명시적으로 써 줘야 한다.
//        라라벨 5.1을 사용한다면 graham-campbell/throttle를 설치하고 적용법은 문서를 참고한다.
//        'middleware' => ['throttle:60,1']
    ], function () {
        /* 환영 메시지 */
        Route::get('/', [
            'as' => 'index',
            'uses' => 'WelcomeController@index',
        ]);
        /* 포럼 API */
        // 아티클
        Route::resource('articles', 'ArticlesController');
        // 태그별 아티클 (중첩 라우트)
        Route::get('tags/{slug}/articles', [
            'as' => 'tags.articles.index',
            'uses' => 'ArticlesController@index',
        ]);
        // 태그
        Route::get('tags', [
            'as' => 'tags.index',
            'uses' => 'ArticlesController@tags',
        ]);
        // 첨부 파일
        Route::resource('attachments', 'AttachmentsController', ['only' => ['store', 'destroy']]);
        // 아티클별 첨부 파일
        Route::resource('articles.attachments', 'AttachmentsController', ['only' => ['index']]);
        // 댓글
        Route::resource('comments', 'CommentsController', ['only' => ['show', 'update', 'destroy']]);
        // 아티클별 댓글
        Route::resource('articles.comments', 'CommentsController', ['only' => ['index', 'store']]);
        // 투표
        Route::post('comments/{comment}/votes', [
            'as' => 'comments.vote',
            'uses' => 'CommentsController@vote',
        ]);
    });
});
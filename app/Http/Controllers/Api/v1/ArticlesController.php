<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends ParentController
{
    public function __construct()
    {
    }

    protected function respondCollection(LengthAwarePaginator $article) {

        return $article->toJson(JSON_PRETTY_PRINT);
    }

    protected function respondCreated(\App\Article $article)
    {
        return parent()->json(
            ['success' => 'created'],
            201,
            ['Location' => '생성한_리소스의_상세보기_API_엔드포인트'],
            JSON_PRETTY_PRINT
        );
    }

    public function tags() {

        return \App\Tag::all();
    }
}

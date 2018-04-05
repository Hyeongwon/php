@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.show'; @endphp

    <div class="page-header">
        <h4>
            포럼
            <small>
              / {{ $article->title }}
            </small>
        </h4>
    </div>

    <article>
        @include('articles.partial.article', compact('article'))

        <p>{!! markdown($article->content) !!}</p>
    </article>

    <div class="text-center action__article">
        @can('update', $article)
        <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-info">
            <i class = "glyphicon-pencil"></i> 글 수정
        </a>
        @endcan
        @can('delete', $article)
        <button class="btn btn-danger button__delete">
            <i class="glyphicon-trash"></i> 글 삭제
        </button>
        @endcan

        <a href="{{ route('articles.index') }}" class="btn btn-default">
            <i class="glyphicon-list"></i> 글 목록
        </a>
    </div>
@stop

@section('script')
    <script>
        $.ajaxSetup({

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.button__delete').on('click', function(e){
            var articleId = {{ $article->id }}
            if(confirm('글을 삭제합니다.')) {
                $.ajax({
                    type: 'DELETE',
                    url: '/articles/' + articleId
                }).then(function () {
                    window.location.href = '/articles';
                });

                alert(articleId);
            }
        });
    </script>
@stop
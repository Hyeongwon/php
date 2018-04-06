@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.index';@endphp
    <div class="page-header">
        <h4>포럼<small> / 글 목록</small></h4>
    </div>

    <div class="text-right">
        <a href="{{ route('articles.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle"></i> 새 글 쓰기
        </a>
        <div class="btn-group sort__article">
            <button type="button" class ="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class = "fa fa-sort"></i> 목록 정렬 <span class ="create"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                {{--@foreach(config('project.sorting') as $column => $text)--}}
                    {{--<li {!! request()->input('sort') == $column ? 'class="active"' : '' !!}>--}}
                       {{--{!! link_for_sort($column, $text) !!}--}}
                    {{--</li>--}}
                {{--@endforeach--}}
            </ul>
            <h1>{{config('project.sorting.view_count') }}</h1>
        </div>
    </div>

    <div class="row container__article">
        <div class="col-md-3 sidebar__article">
            <aside>
                @include('articles.partial.search')
                @include('tags.partial.index');
            </aside>
        </div>
        <div class="col-md-9 list__article">
            <article>
                @forelse($articles as $article)
                    @include('articles.partial.article', compact('article'))
                @empty
                    <p class="text-center text-danger">
                        {{ trans('forum.articles.empty') }}
                    </p>
                @endforelse
            </article>

            @if($articles->count())
                <div class="text-center paginator__article">
                    {!! $articles->appends(request()->except('page'))->render() !!}
                </div>
            @endif
        </div>
    </div>
@stop
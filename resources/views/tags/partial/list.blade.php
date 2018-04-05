@if($tags->count())
    <ul class="tags__article">
        @foreach($tags as $tag)
            <li><a href="{{ route('tags.articles.index', $tags->slug) }}">{{ $tag->name }}
                </a></li>
        @endforeach
    </ul>
@endif
<p class="lead">
    <i class="glyphicon-tag"></i>
    태그
</p>

<ul>
    @foreach($allTags as $tag)
        <li {!! str_contains(request()->path(), $tag->slug) ? 'class="active"' : '' !!}>
            <a href="{{ route('tags.articles.index', $tag->slug) }}">
                {{ $tag->name }}
                @if ($count = $tag->articles->count())
                    <span class="badge badge">
            {{ $count }}
          </span>
                @endif
            </a>
        </li>
    @endforeach
</ul>
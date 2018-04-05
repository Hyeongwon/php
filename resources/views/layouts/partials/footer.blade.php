<footer class="container footer__master">
    <ul class="list-inline pull-right">
        <li>
            <i class="fa fa-language"></i>
        </li>
        {{--@foreach (config('project.locales') as $locale => $language)
            <li {!! ($locale == $currentLocale ) ? 'class="active"' : '' !!}>
                <a href="{{ route('locale', ['locale' => $locale, 'return' => urlencode($currentUrl)]) }}">
                    {{ $language }}
                </a>
            </li>
        @endforeach--}}
    </ul>

    <div>
        &copy; {{ date('Y') }}
        <a href="{{ config('project.url') }}">
            {{ config('app.name') }}
        </a>
    </div>
</footer>

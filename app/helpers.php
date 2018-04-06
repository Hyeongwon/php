<?php
/**
 * Created by PhpStorm.
 * User: byunhyeongwon
 * Date: 2018. 4. 4.
 * Time: AM 9:42
 */

if(! function_exists('markdown')) {

    function markdown($text = null) {

        return app(ParsedownExtra::class) -> text($text);
    }

    function gravatar_url($email, $size = 48) {

        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
    }

    function gravatar_profile_url($email) {

        return sprintf("//www.gravatar.com/%s", md5($email));
    }

    function attachments_path($path = '') {

        return public_path('files'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    function format_filesize($bytes) {

        if(! is_numeric($bytes)) return 'NaN';

        $decr = 1024;
        $step = 0;
        $suffix = ['bytes', 'KB', 'MB'];

        while(($bytes / $decr) > 0.9) {

            $bytes = $bytes / $decr;
            $step ++;
        }

        return round($bytes, 2).$suffix[$step];
    }

    function link_for_sort($column, $text, $params = []) {

        $direction = request()->input('order');
        $reverse = ($direction == 'asc') ? 'desc' : 'asc';

        if(request()->input('sort') == $column) {

            $text = sprintf("%s %s",
                $direction = 'asc'
                ? '<i class = "fa fa-sort-alpha-asc"></i>)'
                : '<i class = "fa fa-sort_alpha-desc"></i>',
            $text
            );
        }

        $queryString = http_build_query(array_merge(['sort', 'order'],
            request()->except(['sort', 'order']),
            ['sort' => $column, 'order' => $reverse],
            $params
        ));

        return sprintf(
            '<a href="%s?%s">%s</a>',
            urldecode(request()->url()),
            $queryString,
            $text
        );
    }


}
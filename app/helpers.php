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
}
<?php

    namespace App\Helpers;


    class Helper
    {
        public static function make_slug($string) {
            $slug = preg_replace('/\s+/u', '-', trim($string));
            $slug = str_replace("/","",$slug);
            $slug = str_replace("?","",$slug);
            return strtolower($slug);
        }
    }

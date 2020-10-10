<?php

if (! function_exists('trans_fb')) {
    /**
     * Translate the given message with a fallback string if none exists.
     *
     * @param  string  $id
     * @param  string  $fallback
     * @param  array   $parameters
     * @param  string  $domain
     * @param  string  $locale
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    function trans_fb($id, $fallback = '', $parameters = [], $locale = 'uk')
    {
        return ($id === ($translation = trans($id, $parameters, $locale))) ? $fallback : $translation;
    }
}

if(! function_exists('mb_ucfirst')) {
    function mb_ucfirst($str) {
        //$str = mb_strtolower($str);
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }
}

if(! function_exists('parseUrl')) {
    function parseUrl($url) {

        $path = explode('/', parse_url($url, PHP_URL_PATH));

        array_shift($path);

        if(reset($path) === 'ru') {
            app()->setLocale('ru');
            array_shift($path);
        }

        $path = explode('-', reset($path));
        //$this->getCity();

        if(!empty(parse_url($url, PHP_URL_QUERY))){
            $query = explode('&', parse_url($url, PHP_URL_QUERY));
        } else {
            $query = '';
        }

        $locale = app()->getLocale();

        return compact('path', 'query', 'locale');
    }
}



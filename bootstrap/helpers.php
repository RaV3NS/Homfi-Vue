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
    function trans_fb($id, $fallback, $parameters = [], $locale = 'uk')
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

<?php

if (! function_exists('p')) {
    function p(mixed $object): void
    {
        echo '<pre>';
        print_r($object);
        echo '</pre>';
    }
}

if (! function_exists('l')) {
    function l(string $s): string
    {
        global $_translations;
        return $_translations[$s] ?? $s;
    }
}

if (! function_exists('getTranslations')) {
    function getTranslations(string $isoCode): void
    {
        global $_translations;
        $fileName = resource_path() . '/translations/' . strtolower(trim($isoCode)) . '.json';
        $_translations = file_exists($fileName) ? json_decode(file_get_contents($fileName), true) : [];
    }
}

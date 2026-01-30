<?php

if (!function_exists('localized_url')) {
    function localized_url($path = '') {
        $locale = session('locale', 'id');
        $prefix = $locale === 'en' ? '/en' : '';

        return url($prefix . '/' . ltrim($path, '/'));
    }
}

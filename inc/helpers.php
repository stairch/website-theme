<?php

function normalize_url(string $url): string {
    // remove https://, www. and trailing /
    $url = preg_replace('#^https?://(www\.)?#', '', $url);
    $url = rtrim($url, '/');

    $parts = explode('/', $url);
    if (count($parts) > 2) {
        return 'Website';
    }

    return $url;
}

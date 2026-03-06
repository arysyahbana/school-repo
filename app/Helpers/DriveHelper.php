<?php

if (! function_exists('googleDrivePreviewUrl')) {
    function googleDrivePreviewUrl(string $url): ?string
    {
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
        }

        return null;
    }
}

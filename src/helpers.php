<?php

use Carbon\Carbon;

if (!function_exists('humanReadableFileSize')) {
    function humanReadableFileSize(float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes < 1) {
            return '0 '.$units[1];
        }

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}

if (!function_exists('ageInDays')) {
    function ageInDays(Carbon $date): string
    {
        return number_format(round($date->diffInMinutes() / (24 * 60), 2), 2).' ('.$date->diffForHumans().')';
    }
}

if (!function_exists('isLargeFileSize')) {
    function isLargeFileSize(float $bytes, int $gigabytes = 4): bool
    {
        if ($bytes < 1024) {
            return false;
        }

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        if ($i == 3 && $bytes >= $gigabytes) {
            return true;
        }

        return $i > 3;
    }
}

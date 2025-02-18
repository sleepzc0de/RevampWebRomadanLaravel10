<?php

namespace App\Helpers;

class VersionHelper
{
    public static function getFullVersion()
    {
        $version = config('app.version');
        return sprintf(
            'v%s.%s.%s-%s+build.%s',
            $version['major'],
            $version['minor'],
            $version['patch'],
            $version['release'],
            $version['build']
        );
    }

    public static function getShortVersion()
    {
        $version = config('app.version');
        return sprintf(
            'v%s.%s.%s',
            $version['major'],
            $version['minor'],
            $version['patch']
        );
    }

    public static function getBuildInfo()
    {
        $version = config('app.version');
        return [
            'version' => self::getFullVersion(),
            'timestamp' => $version['timestamp'],
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];
    }
}

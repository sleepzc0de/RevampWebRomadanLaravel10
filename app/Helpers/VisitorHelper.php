<?php

namespace App\Helpers;

class VisitorHelper
{
    private const BOT_PATTERNS = '/bot|crawl|spider|slurp|facebookexternalhit|whatsapp|telegrambot|curl|wget|python-requests|scrapy|headless/i';

    public static function isBot(?string $userAgent): bool
    {
        return (bool) preg_match(self::BOT_PATTERNS, (string) $userAgent);
    }
}

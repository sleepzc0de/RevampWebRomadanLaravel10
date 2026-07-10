<?php

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Sanitize input: buang tag HTML dan karakter kontrol.
     *
     * Catatan: JANGAN meng-encode entity HTML di sini — escaping adalah
     * tanggung jawab lapisan output (Blade {{ }}). Encoding di input
     * menyebabkan double-escaping dan merusak pencarian (mis. "R&D").
     */
    public static function sanitizeInput($input)
    {
        if (is_string($input)) {
            // Remove HTML and PHP tags
            $input = strip_tags($input);
            // Remove control characters
            $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
            return trim($input);
        }
        return $input;
    }

    /**
     * Validate file extension
     */
    public static function isValidImageFile($filename)
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $allowed);
    }

    /**
     * Escape output for safe HTML rendering
     */
    public static function escapeOutput($output)
    {
        return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    }
}

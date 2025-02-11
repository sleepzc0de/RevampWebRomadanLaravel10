<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeHtmlInput
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Remove HTML tags except allowed ones
                $value = strip_tags($value, '<p><br><strong><em><ul><li><ol><h1><h2><h3><h4><h5><h6>');

                // Convert special characters to HTML entities
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

                // Remove any potential script injection
                $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);

                // Remove any potential iframe injection
                $value = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $value);

                // Remove any potential onEvent handlers
                $value = preg_replace('/\bon\w+=\S+(?=.*>)/i', '', $value);
            }
        });

        $request->merge($input);
        return $next($request);
    }
}

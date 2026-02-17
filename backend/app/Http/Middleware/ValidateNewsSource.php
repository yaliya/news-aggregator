<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateNewsSource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sourceParam = $request->query('source');

        if ($sourceParam === null || $sourceParam === '') {
            return $next($request);
        }

        $requestedSources = array_filter(array_map('trim', explode(',', $sourceParam)));

        $configuredSources = array_keys(config('news.sources', []));

        $invalid = array_diff($requestedSources, $configuredSources);

        if (! empty($invalid)) {
            return response()->json([
                'message' => 'One or more sources are invalid.',
                'invalid_sources' => array_values($invalid),
            ], 422);
        }

        return $next($request);
    }
}


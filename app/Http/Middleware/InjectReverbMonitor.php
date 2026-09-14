<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectReverbMonitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->routeIs('monitor') && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();

            if ($content !== false && str_contains($content, '</body>')) {
                $script = view('partials.reverb-monitor')->render();
                $response->setContent(str_replace('</body>', $script . '</body>', $content));
            }
        }

        return $response;
    }
}

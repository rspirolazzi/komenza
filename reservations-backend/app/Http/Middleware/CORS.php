<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CORS
{
    public function handle($request, Closure $next)
    {
        $origin = $request->headers->get('Origin') ?: '*';

        // Logging información relevante
        /*\Log::info('CORS Middleware', [
            'method' => $request->getMethod(),
            'origin' => $origin,
            'headers' => $request->headers->all()
        ]);*/

        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'     => 'Content-Type, X-Auth-Token, Origin, Authorization, Accept',
        ];
        
        if ($request->getMethod() == "OPTIONS") {
            //\Log::info('CORS Middleware: OPTIONS request detected, returning early.');
            return response('OK', 200, $headers);
        }

        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        //\Log::info('CORS Middleware: Response headers set.', $headers);
        return $response;
    }
}
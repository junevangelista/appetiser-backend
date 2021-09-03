<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ConvertCamelCaseToSnakeCase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $items = [];
        foreach ($request->all() as $key => $value) {
            $items[Str::snake($key)] = $value;
        }

        $request->replace($items);

        return $next($request);
    }
}

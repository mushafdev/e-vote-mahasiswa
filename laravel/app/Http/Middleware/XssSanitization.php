<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Formatter;
class XssSanitization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        $input = $request->all();
        
        array_walk_recursive($input, function(&$input) {
            $input = Formatter::jsEscape(strip_tags(htmlspecialchars(htmlentities($input, ENT_QUOTES, 'UTF-8'))));
            
        });
        
        $request->merge($input);
            return $next($request);
    }
}

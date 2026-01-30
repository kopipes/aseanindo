<?php
 
 namespace App\Http\Middleware;
 
 use Closure;
 use Illuminate\Http\Request;
 
 class RedirectTrailingSlash
 {
     // /**
     //  * Handle an incoming request.
     //  *
     //  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     //  */
     public function handle(Request $request, Closure $next)
     {
         if(preg_match('/.+\/$/', $request->getRequestUri())){
             $url = rtrim($request->getRequestUri(), '/');
             return redirect($url, 301);
         }
         return $next($request);
     }
 }
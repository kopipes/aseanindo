<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1);

        if ($locale === 'en') {
            App::setLocale('en');
            Session::put('locale', 'en');
        } else {
            App::setLocale('id');
            Session::put('locale', 'id');
            $locale = 'id';
        }

        if ($locale === 'en' && $request->segment(1) !== 'en') {
            return redirect('/en' . $request->getPathInfo());
        } elseif ($locale === 'id' && $request->segment(1) === 'en') {
            return redirect($request->getPathInfo());
        }

        return $next($request);
    }
}

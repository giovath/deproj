<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLanguage
{

    public function handle($request, Closure $next)
    {

        $language = substr(
            $request->getPreferredLanguage(['pt_BR', 'es']),
            0,
            2
        );


        if ($language === 'es') {

            App::setLocale('es');
        } else {

            App::setLocale('pt_BR');
        }


        return $next($request);
    }
}

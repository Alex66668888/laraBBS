<?php

namespace App\Http\Middleware;

use Closure;

class ChangeLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // 接收 Accept-Language 头部信息
        $language = $request->header('accept-language');
        if ($language) {
            // 设置本地语言
            \App::setLocale($language);
        }

        return $next($request);
    }
}

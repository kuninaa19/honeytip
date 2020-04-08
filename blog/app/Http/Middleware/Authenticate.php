<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // 로그인 안되어있다면 /auth/login 이름의 라우터 실행
        if (! $request->expectsJson()) {
//            return url('https://honeytip.kro.kr');
            return route('login');
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class CORS
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

//        $r_url = isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'';
//        $allow_url = [
//            'https://honeytip.kro.kr/test.html',
//            'https://honeytip.kro.kr',
////            'https://honeytip.p-e.kr/csrf_token',
////            'https://honeytip.p-e.kr/login/auth'
//        ];
//
//        if( array_search($r_url, $allow_url) !== false ){
//            header('Access-Control-Allow-Origin: '.$r_url);
//
//        }

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Credentials: false');
        header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");
        return $next($request);
    }
}

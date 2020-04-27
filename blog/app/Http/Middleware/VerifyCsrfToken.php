<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/make_id',
        '/login',
        'posts',
        'posts/*',
        'comments',
        'comments/*',
        'reply',
        'reply/*',
        'user',
        'user/*',
        'like',
        'like/*'
    ];
}

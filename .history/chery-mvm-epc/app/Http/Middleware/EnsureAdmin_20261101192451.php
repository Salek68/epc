<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // // کاربر وارد نشده باشد
        // if (!Auth::check()) {
        //     abort(401, 'ابتدا باید وارد حساب کاربری شوید.');
        // }

        // // کاربر مدیر نباشد
        // if (!Auth::user()->is_admin) {
        //     abort(403, 'شما اجازه دسترسی به پنل مدیریت را ندارید.');
        // }

        return $next($request);
    }
}

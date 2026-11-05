<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(
        Request $request,
        Closure $next,
        string $role = 'user'
    ): Response {

        $user = auth()->user();

        // مدیر سایت به همه بخش‌ها دسترسی دارد
        if ($user && $user->is_admin) {
            return $next($request);
        }

        if (!$user) {
            return redirect()->route('login');
        }

        $query = $user->subscriptions()
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>', now());

        if ($role === 'user') {
            // اشتراک کاربر یا فروشنده برای کاتالوگ معتبر است
            $hasAccess = (clone $query)
                ->whereIn('role', ['user', 'seller'])
                ->exists();

        } elseif ($role === 'seller') {
            // بخش فروشندگی فقط با اشتراک فروشنده قابل دسترسی است
            $hasAccess = (clone $query)
                ->where('role', 'seller')
                ->exists();

        } else {
            $hasAccess = false;
        }

        if (!$hasAccess) {
            return redirect()
                ->route('subscriptions.index')
                ->with(
                    'subscription_error',
                    $role === 'seller'
                        ? 'برای استفاده از امکانات فروشندگی، اشتراک فروشنده لازم دارید.'
                        : 'برای استفاده از کاتالوگ، ابتدا اشتراک تهیه یا تمدید کنید.'
                );
        }

        return $next($request);
    }
}

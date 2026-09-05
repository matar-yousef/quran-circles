<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasHalaqa
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // التحقق مما إذا كان المحفظ لا يمتلك أي حلقة
        if ($user->halaqas()->count() === 0) {

            // 🔧 تم تعديل الأسماء هنا إلى المفرد (halaqa)
            if (
                ! $request->routeIs('halaqa.create') &&
                ! $request->routeIs('halaqa.store') &&
                ! $request->routeIs('logout')
            ) {

                return redirect()->route('halaqa.create')
                    ->with('warning', 'يرجى إنشاء الحلقة الخاصة بك أولاً حتى تتمكن من استخدام النظام.');
            }
        }

        return $next($request);
    }
}

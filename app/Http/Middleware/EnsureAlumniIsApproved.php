<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAlumniIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Administrators and Pengurus always have bypass access
        if ($user->isAdmin() || $user->isPengurus()) {
            return $next($request);
        }

        // Alumni must have active status and verified alumnus profile
        if ($user->status !== 'active' || ! ($user->alumnus?->is_verified)) {
            return redirect()->route('profile.show')->with('warning', 'Fitur ini hanya dapat diakses setelah keanggotaan alumni Anda disetujui oleh pengurus.');
        }

        return $next($request);
    }
}

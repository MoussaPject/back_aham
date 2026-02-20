<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Si pas connecté, on laisse le middleware auth:sanctum gérer
        if (! $user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $userRole = $user->role ?? null;

        // Si aucun rôle exigé n'est passé, ou si le rôle de l'utilisateur ne correspond pas
        if (empty($roles) || ! in_array($userRole, $roles, true)) {
            return response()->json([
                'message' => 'Forbidden. Accès réservé aux rôles: ' . implode(',', $roles),
            ], 403);
        }

        return $next($request);
    }
}

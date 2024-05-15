<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\AuthController;
use App\Models\User;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (in_array("any", $roles)) {
            return $next($request);
        }
        $userData=new User();
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $parts = explode(' ', $authorizationHeader);

        if (count($parts) !== 2 || $parts[0] !== 'Bearer') {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $token = trim($parts[1]);

        if (!$token) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

            $authController = new AuthController();

            $role = $authController->getRoleByToken($token);
            if (empty($role)) {
                return response()->json( ['error' => 'Unauthorized'], 403);
            }

            if (!in_array($role, $roles)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

        return $next($request);
    }
 
}
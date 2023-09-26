<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json(['status' => 'error'], Response::HTTP_UNAUTHORIZED);
        }

        if (!in_array(auth()->user()->role, [UserRole::ADMINISTRATOR, UserRole::EDITOR])) {
            auth()->logout();
            return response()->json(['status' => 'error'], Response::HTTP_FORBIDDEN);
        }

        return $this->respondWithToken($token);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['status' => 'ok']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
                                    'access_token' => $token,
                                    'token_type' => 'bearer',
                                    'expires_in' => auth()->factory()->getTTL() * 60,
                                    'status' => 'ok',
                                ]);
    }
}

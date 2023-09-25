<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    public function store(RegistrationRequest $request): JsonResponse
    {
        $user = User::create($request->all());

        return response()->json(['status' => 'ok',])->setStatusCode(Response::HTTP_OK);
    }
}

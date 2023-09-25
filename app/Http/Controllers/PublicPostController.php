<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PublicPostController extends Controller
{
    public function index(int $page = 1): JsonResponse
    {
        $posts = Post::orderBy('created_at')->limit(10)->offset(10 * ($page - 1))->get();

        return response()->json([
                                    'status' => 'ok',
                                    'page' => $page,
                                    'data' => $posts,
                                ])->setStatusCode(Response::HTTP_OK);
    }
}

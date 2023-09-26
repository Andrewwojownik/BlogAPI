<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PublicPostController extends Controller
{

    public function __construct(
        private readonly PostRepository $postRepository,
    )
    {
    }

    public function index(int $page = 1): JsonResponse
    {
        $posts = $this->postRepository->getAllWithPagination($page, 10);

        return response()->json([
                                    'status' => 'ok',
                                    'page' => $page,
                                    'data' => $posts,
                                ])->setStatusCode(Response::HTTP_OK);
    }
}

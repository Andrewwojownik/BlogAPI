<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AdminPostCreateRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class AdminPostController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        $page = (int)$request->get('page', 1);
        $posts = $this->postRepository->getAllWithPagination($page, 50);

        return response()->json([
                                    'status' => 'ok',
                                    'page' => $page,
                                    'data' => $posts,
                                ])->setStatusCode(Response::HTTP_OK);
    }

    public function store(AdminPostCreateRequest $request): JsonResponse
    {
        $post = Post::create($request->all());

        return response()->json([
                                    'status' => 'ok',
                                    'data' => $post->uuid
                                ])->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(string $uuid): JsonResponse
    {
        $post = $this->postRepository->getOneByUuid(Uuid::fromString($uuid));

        if (!$post) {
            return response()->json([
                                        'status' => 'error',
                                    ])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return response()->json([
                                    'status' => 'ok',
                                    'data' => $post,
                                ])->setStatusCode(Response::HTTP_OK);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        //TODO
        return response()->json([
                                    'status' => 'ok',
                                ])->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $post = $this->postRepository->getOneByUuid(Uuid::fromString($uuid));

        if (!$post) {
            return response()->json([
                                        'status' => 'error',
                                    ])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $post->delete();

        return response()->json([
                                    'status' => 'ok',
                                ])->setStatusCode(Response::HTTP_OK);
    }
}

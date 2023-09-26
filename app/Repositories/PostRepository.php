<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PostRepository implements \App\Contracts\PostRepository
{
    public function getAllWithPagination(int $page, int $perPage = 10): Collection
    {
        return Post::orderBy('created_at')->limit(10)->offset(10 * ($page - 1))->get();
    }

    public function getOneByUuid(UuidInterface $uuid): ?Post
    {
        return Post::where('uuid', $uuid)->first();
    }
}

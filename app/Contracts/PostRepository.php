<?php
declare(strict_types=1);

namespace App\Contracts;

use App\Models\Post;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface PostRepository
{
    public function getAllWithPagination(int $page, int $perPage = 10): Collection;

    public function getOneByUuid(UuidInterface $uuid): ?Post;
}

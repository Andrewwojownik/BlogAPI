<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userUuid = User::take(1)->first()->uuid;
        for ($i = 0; $i < 100; ++$i) {
            Post::insert([
                             'uuid' => Str::uuid(),
                             'title' => Str::random(20),
                             'content' => Str::random(1000),
                             'author_uuid' => $userUuid,
                             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                         ]);
        }
    }
}

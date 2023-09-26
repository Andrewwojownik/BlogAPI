<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
                                    'name' => 'Test Admin',
                                    'email' => 'testadmin@example.com',
                                    'role' => UserRole::ADMINISTRATOR,
                                    'password' => Hash::make('test1234567890')
                                ]);

        User::factory()->create([
                                    'name' => 'Test Editor',
                                    'email' => 'testeditor@example.com',
                                    'role' => UserRole::EDITOR,
                                    'password' => Hash::make('0987654321test')
                                ]);

        User::factory()->create([
                                    'name' => 'Test User',
                                    'email' => 'testuser@example.com',
                                    'role' => UserRole::USER,
                                    'password' => Hash::make('test0987654321')
                                ]);

        $this->call([
                        PostSeeder::class,
                    ]);
    }
}

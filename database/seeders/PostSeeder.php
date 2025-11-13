<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
        ->hasPosts(50)
        ->create([
            'name' => 'Test User1',
            'email' => '1test@example.com',
        ]);
        User::factory()
        ->hasPosts(50)
        ->create([
            'name' => 'Test User2',
            'email' => '2test@example.com',
        ]);
        
    }
}

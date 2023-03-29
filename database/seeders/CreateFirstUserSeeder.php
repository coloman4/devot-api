<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateFirstUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Benjamin',
            'email' => 'benjamin@email.com',
            'password' => 'somepass123',
        ]);

        $user->balance = 1000;
        $user->save();

        Category::create([
            'name' => 'Racuni',
            'user_id' => $user->id
        ]);

        Category::create([
            'name' => 'Kupovina',
            'user_id' => $user->id
        ]);

        Category::create([
            'name' => 'Auto',
            'user_id' => $user->id
        ]);

        Category::create([
            'name' => 'Dodatni Troskovi',
            'user_id' => $user->id
        ]);

    }
}

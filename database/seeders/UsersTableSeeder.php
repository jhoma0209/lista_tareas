<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin Principal',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'María García',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
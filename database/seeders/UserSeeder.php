<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing users to avoid duplicates
        User::truncate();

        // Create sample users
        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao@ciaopet.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@ciaopet.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('Usuários criados com sucesso!');
        $this->command->info('João Silva - joao@ciaopet.com - password123');
        $this->command->info('Maria Santos - maria@ciaopet.com - password123');
    }
}

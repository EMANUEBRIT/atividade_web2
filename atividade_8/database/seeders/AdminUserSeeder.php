<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@biblioteca.com'], // busca por este email
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
        $this->command->info('Usuário admin criado ou já existente.');
    }
}

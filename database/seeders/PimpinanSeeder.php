<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class PimpinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pimpinan@crm.com'],
            [
                'name' => 'Pimpinan',
                'password' => bcrypt('password'),
                'role' => 'pimpinan',
            ]
        );
    }
}

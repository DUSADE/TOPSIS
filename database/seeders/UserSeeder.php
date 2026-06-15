<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@crm.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 5 Sales Users
        foreach (range(1, 5) as $i) {
            \App\Models\User::updateOrCreate(
                ['email' => "sales{$i}@crm.com"],
                [
                    'name' => "Sales {$i}",
                    'password' => bcrypt('password'),
                    'role' => 'sales',
                ]
            );
        }

        // Generic Sales User (Old compat)
        \App\Models\User::updateOrCreate(
            ['email' => 'sales@crm.com'],
            [
                'name' => 'Sales Staff',
                'password' => bcrypt('password'),
                'role' => 'sales',
            ]
        );
    }
}

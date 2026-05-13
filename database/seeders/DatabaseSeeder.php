<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@mosip.go.id'],
            [
                'name' => 'Admin MOSIP',
                'password' => 'password',
                'role' => 'Super Admin',
                'instansi' => 'MOSIP Pusat',
                'status' => 'aktif',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'tekpol@mosip.go.id'],
            [
                'name' => 'Operator Tekpol',
                'password' => 'password',
                'role' => 'Tekpol',
                'instansi' => 'Bidang Tekpol',
                'status' => 'aktif',
            ]
        );
    }
}

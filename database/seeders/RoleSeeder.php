<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'admin',
            'customer'
        ];

        foreach ($names as $name) {
            $role = Role::query()->where('name', $name)->first();

            if (!$role) {
                Role::create([
                    'name' => $name,
                ]);
            }
        }
    }
}

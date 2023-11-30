<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'Administrator'],
            ['id' => 2, 'name' => 'User'],
        ];

        foreach ($items as $item) {
            Role::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}

<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Add-Book',
            'Update-Book',
            'Delete-Book',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['guard_name' => 'api', 'name' => $permission]);
        }
    }
}

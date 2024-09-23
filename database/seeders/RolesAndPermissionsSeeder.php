<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the "Auction Editor" role
        $auctionEditor = Role::create(['name' => 'auction_editor']);

        // Define permissions
        $permissions = [
            'create auction',
            'edit auction',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $auctionEditor ->givePermissionTo($permission);
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}

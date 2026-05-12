<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Tickets
            'ticket.view_all', 'ticket.view_own', 'ticket.create',
            'ticket.edit', 'ticket.delete', 'ticket.assign', 'ticket.close',
            // Users
            'user.view', 'user.create', 'user.edit', 'user.delete',
            // Settings
            'settings.manage', 'category.manage', 'department.manage',
            // Reports
            'report.view',
            // Knowledge Base
            'kb.view', 'kb.create', 'kb.edit', 'kb.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Admin - semua akses
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Agent - kelola tiket yang di-assign
        $agent = Role::firstOrCreate(['name' => 'agent']);
        $agent->givePermissionTo([
            'ticket.view_all', 'ticket.edit', 'ticket.close',
            'kb.view', 'kb.create', 'kb.edit',
            'report.view',
        ]);

        // User - buat & lihat tiket sendiri
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo([
            'ticket.create', 'ticket.view_own', 'kb.view',
        ]);
    }
}
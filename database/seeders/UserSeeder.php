<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $itDept = Department::where('code', 'IT')->first();

        // Admin
        $admin = User::create([
            'name'          => 'Administrator',
            'email'         => 'admin@helpdesk.com',
            'password'      => Hash::make('password'),
            'department_id' => $itDept?->id,
            'employee_id'   => 'EMP001',
        ]);
        $admin->assignRole('admin');

        // Agent
        $agent = User::create([
            'name'          => 'IT Support Agent',
            'email'         => 'agent@helpdesk.com',
            'password'      => Hash::make('password'),
            'department_id' => $itDept?->id,
            'employee_id'   => 'EMP002',
        ]);
        $agent->assignRole('agent');

        // End User
        $user = User::create([
            'name'        => 'John Doe',
            'email'       => 'user@helpdesk.com',
            'password'    => Hash::make('password'),
            'employee_id' => 'EMP003',
        ]);
        $user->assignRole('user');
    }
}
<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name'        => 'Information Technology',
                'code'        => 'IT',
                'description' => 'Departemen IT & infrastruktur',
                'is_active'   => true,
            ],
            [
                'name'        => 'Human Resources',
                'code'        => 'HR',
                'description' => 'Departemen sumber daya manusia',
                'is_active'   => true,
            ],
            [
                'name'        => 'Finance & Accounting',
                'code'        => 'FIN',
                'description' => 'Departemen keuangan dan akuntansi',
                'is_active'   => true,
            ],
            [
                'name'        => 'Operations',
                'code'        => 'OPS',
                'description' => 'Departemen operasional',
                'is_active'   => true,
            ],
            [
                'name'        => 'Marketing',
                'code'        => 'MKT',
                'description' => 'Departemen pemasaran',
                'is_active'   => true,
            ],
            [
                'name'        => 'Sales',
                'code'        => 'SLS',
                'description' => 'Departemen penjualan',
                'is_active'   => true,
            ],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }
    }
}

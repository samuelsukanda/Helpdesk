<?php

namespace Database\Seeders;

use App\Models\SlaPolicy;
use Illuminate\Database\Seeder;

class SlaPolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            [
                'name'                   => 'Low Priority SLA',
                'priority'               => 'low',
                'response_time_hours'    => 24,  // 1 hari
                'resolution_time_hours'  => 72,  // 3 hari
                'is_active'              => true,
            ],
            [
                'name'                   => 'Medium Priority SLA',
                'priority'               => 'medium',
                'response_time_hours'    => 8,   // 8 jam
                'resolution_time_hours'  => 24,  // 1 hari
                'is_active'              => true,
            ],
            [
                'name'                   => 'High Priority SLA',
                'priority'               => 'high',
                'response_time_hours'    => 4,   // 4 jam
                'resolution_time_hours'  => 8,   // 8 jam
                'is_active'              => true,
            ],
            [
                'name'                   => 'Critical Priority SLA',
                'priority'               => 'critical',
                'response_time_hours'    => 1,   // 1 jam
                'resolution_time_hours'  => 4,   // 4 jam
                'is_active'              => true,
            ],
        ];

        foreach ($policies as $policy) {
            SlaPolicy::firstOrCreate(
                ['priority' => $policy['priority']],
                $policy
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'      => 'Hardware',
                'icon'      => 'fa-desktop',
                'color'     => '#3B82F6',
                'is_active' => true,
                'subs'      => [
                    'Komputer / Laptop',
                    'Monitor',
                    'Printer / Scanner',
                    'Keyboard / Mouse',
                    'UPS / Power Supply',
                    'Proyektor',
                    'Telepon / PABX',
                ],
            ],
            [
                'name'      => 'Software',
                'icon'      => 'fa-code',
                'color'     => '#8B5CF6',
                'is_active' => true,
                'subs'      => [
                    'Microsoft Office',
                    'Operating System (Windows)',
                    'Aplikasi Internal',
                    'Antivirus',
                    'Browser',
                    'Driver',
                    'Lisensi Software',
                ],
            ],
            [
                'name'      => 'Network',
                'icon'      => 'fa-network-wired',
                'color'     => '#10B981',
                'is_active' => true,
                'subs'      => [
                    'Koneksi Internet',
                    'WiFi',
                    'LAN / Kabel Jaringan',
                    'VPN',
                    'Firewall',
                    'Switch / Router',
                ],
            ],
            [
                'name'      => 'Account & Access',
                'icon'      => 'fa-user-shield',
                'color'     => '#F59E0B',
                'is_active' => true,
                'subs'      => [
                    'Reset Password',
                    'Pembuatan Akun Baru',
                    'Hak Akses / Permission',
                    'Email Account',
                    'Active Directory',
                    'VPN Account',
                ],
            ],
            [
                'name'      => 'Server & Database',
                'icon'      => 'fa-server',
                'color'     => '#EF4444',
                'is_active' => true,
                'subs'      => [
                    'Server Down',
                    'Backup & Restore',
                    'Database Error',
                    'Web Server',
                    'File Server',
                ],
            ],
            [
                'name'      => 'CCTV & Security',
                'icon'      => 'fa-video',
                'color'     => '#6B7280',
                'is_active' => true,
                'subs'      => [
                    'CCTV',
                    'Access Door',
                    'Alarm System',
                ],
            ],
            [
                'name'      => 'Lainnya',
                'icon'      => 'fa-ellipsis-h',
                'color'     => '#9CA3AF',
                'is_active' => true,
                'subs'      => [
                    'Permintaan Umum',
                    'Konsultasi IT',
                    'Pengadaan Perangkat',
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $subs = $catData['subs'];
            unset($catData['subs']);

            $category = Category::firstOrCreate(
                ['name' => $catData['name']],
                $catData
            );

            foreach ($subs as $subName) {
                SubCategory::firstOrCreate([
                    'category_id' => $category->id,
                    'name'        => $subName,
                ], [
                    'is_active' => true,
                ]);
            }
        }
    }
}

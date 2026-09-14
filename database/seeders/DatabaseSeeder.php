<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'name' => 'Office Network Upgrade',
                'client' => 'PT Tazaka Teknologi',
                'project_type' => 'tazaka_order',
                'progress' => 75,
                'target_completion_date' => '2026-10-15',
                'project_month' => '2026-09-01',
                'description' => 'Network infrastructure upgrade including structured cabling, switching, and network optimization.',
                'notes' => 'Main installation is in progress. Final testing is scheduled after cabling completion.',
                'location' => 'Bandung, West Java',
                'latlong' => json_encode(['lat' => -6.9175, 'lng' => 107.6191]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CCTV Installation - Warehouse',
                'client' => 'PT Sinar Logistik',
                'project_type' => 'subcontract',
                'progress' => 50,
                'target_completion_date' => '2026-10-30',
                'project_month' => '2026-08-01',
                'description' => 'CCTV installation and monitoring system deployment for a logistics warehouse.',
                'notes' => 'Camera installation is halfway completed. NVR configuration will follow.',
                'location' => 'Bekasi, West Java',
                'latlong' => json_encode(['lat' => -6.2383, 'lng' => 106.9756]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Access Control System',
                'client' => 'PT Nusantara Properti',
                'project_type' => 'external',
                'progress' => 30,
                'target_completion_date' => '2026-11-20',
                'project_month' => '2026-09-01',
                'description' => 'Access control deployment for office entrances and restricted areas.',
                'notes' => 'Hardware procurement is complete and installation preparation has started.',
                'location' => 'Jakarta, DKI Jakarta',
                'latlong' => json_encode(['lat' => -6.2088, 'lng' => 106.8456]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fiber Optic Backbone',
                'client' => 'PT Mitra Data Indonesia',
                'project_type' => 'tazaka_order',
                'progress' => 90,
                'target_completion_date' => '2026-09-25',
                'project_month' => '2026-07-01',
                'description' => 'Fiber optic backbone installation connecting multiple building network rooms.',
                'notes' => 'Splicing and final OTDR testing remain.',
                'location' => 'Surabaya, East Java',
                'latlong' => json_encode(['lat' => -7.2575, 'lng' => 112.7521]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smart Building Monitoring',
                'client' => 'PT Citra Mandiri',
                'project_type' => 'external',
                'progress' => 15,
                'target_completion_date' => '2026-12-15',
                'project_month' => '2026-09-01',
                'description' => 'Centralized monitoring solution for building systems and operational equipment.',
                'notes' => 'Initial site survey completed. System design is being finalized.',
                'location' => 'Semarang, Central Java',
                'latlong' => json_encode(['lat' => -6.9667, 'lng' => 110.4167]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

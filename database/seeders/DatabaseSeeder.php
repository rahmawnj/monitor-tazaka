<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            ['Office Network Upgrade','PT Tazaka Teknologi','tazaka_order',75,'2026-10-15','2026-09-01','Network infrastructure upgrade including structured cabling, switching, and optimization.','Main installation is in progress.','Bandung, West Java',-6.9175,107.6191],
            ['CCTV Installation - Warehouse','PT Sinar Logistik','subcontract',50,'2026-10-30','2026-08-01','CCTV installation and monitoring system deployment for a logistics warehouse.','Camera installation is halfway completed.','Bekasi, West Java',-6.2383,106.9756],
            ['Access Control System','PT Nusantara Properti','external',30,'2026-11-20','2026-09-01','Access control deployment for office entrances and restricted areas.','Hardware procurement is complete.','Jakarta, DKI Jakarta',-6.2088,106.8456],
            ['Fiber Optic Backbone','PT Mitra Data Indonesia','tazaka_order',90,'2026-09-25','2026-07-01','Fiber optic backbone installation connecting multiple network rooms.','Splicing and final OTDR testing remain.','Surabaya, East Java',-7.2575,112.7521],
            ['Smart Building Monitoring','PT Citra Mandiri','external',15,'2026-12-15','2026-09-01','Centralized monitoring solution for building systems.','Initial site survey completed.','Semarang, Central Java',-6.9667,110.4167],
            ['Data Center CCTV Upgrade','PT Arunika Data Center','tazaka_order',65,'2026-10-10','2026-09-01','Upgrade CCTV coverage and recording capacity in data center areas.','Camera replacement is underway.','Jakarta, DKI Jakarta',-6.1751,106.8650],
            ['Office WiFi Deployment','PT Karya Digital','subcontract',40,'2026-10-22','2026-09-01','Enterprise WiFi deployment across three office floors.','Access point installation has started.','Tangerang, Banten',-6.1783,106.6319],
            ['Gate Barrier Automation','PT Sentosa Industrial','external',80,'2026-09-30','2026-08-01','Automated gate barrier and vehicle access integration.','Integration testing is in progress.','Karawang, West Java',-6.3227,107.3376],
            ['Warehouse Network Expansion','PT Prima Distribusi','tazaka_order',55,'2026-11-05','2026-09-01','Network expansion for new warehouse operational zones.','Cabling materials are on site.','Cikarang, West Java',-6.2615,107.1528],
            ['Meeting Room AV System','PT Bintang Konsultan','external',25,'2026-11-12','2026-09-01','Audio visual system installation for executive meeting rooms.','Equipment selection is finalized.','Jakarta, DKI Jakarta',-6.2297,106.7990],
            ['Fiber Link Metro','PT Lintas Komunikasi','subcontract',70,'2026-10-05','2026-08-01','Metro fiber link deployment between branch offices.','Most links have passed initial testing.','Depok, West Java',-6.4025,106.7942],
            ['Retail CCTV Rollout','PT Sukses Retail Indonesia','tazaka_order',35,'2026-12-01','2026-09-01','CCTV rollout across multiple retail locations.','First batch of stores is underway.','Bogor, West Java',-6.5950,106.8166],
            ['Server Room Renovation','PT Media Nusantara','external',60,'2026-10-28','2026-08-01','Server room electrical, rack, cooling, and monitoring renovation.','Rack installation is in progress.','Yogyakarta, DI Yogyakarta',-7.7956,110.3695],
            ['IP Intercom Installation','PT Harmoni Residence','subcontract',45,'2026-11-18','2026-09-01','IP intercom installation for residential access points.','Indoor units are being installed.','Bandung, West Java',-6.9147,107.6098],
            ['Smart Parking System','PT Parkir Modern','external',20,'2026-12-20','2026-09-01','Smart parking monitoring and access control system.','System architecture is being prepared.','Surabaya, East Java',-7.2459,112.7378],
            ['Campus Network Refresh','Universitas Teknologi Nusantara','tazaka_order',85,'2026-10-02','2026-08-01','Campus network switching and backbone refresh.','Final switch configuration remains.','Malang, East Java',-7.9666,112.6326],
            ['Hotel Security System','PT Pesona Hospitality','external',50,'2026-11-25','2026-09-01','Integrated CCTV and access control for hotel facilities.','Floor-by-floor installation is ongoing.','Bali, Indonesia',-8.6500,115.2167],
            ['Industrial IoT Monitoring','PT Maju Manufaktur','subcontract',10,'2027-01-15','2026-10-01','IoT monitoring for production equipment and utilities.','Pilot device installation is planned.','Semarang, Central Java',-6.9904,110.4229],
            ['Branch Office Connectivity','PT Satu Jaringan','tazaka_order',95,'2026-09-20','2026-08-01','Connectivity deployment for branch office network infrastructure.','Final documentation and handover remain.','Makassar, South Sulawesi',-5.1477,119.4327],
            ['Command Center Display','PT Garda Teknologi','external',32,'2026-12-10','2026-09-01','Large display and monitoring dashboard for command center operations.','Display layout and dashboard integration are in progress.','Bandung, West Java',-6.9175,107.6191],
        ];

        foreach ($projects as $index => $project) {
            DB::table('projects')->insert([
                'name' => $project[0],
                'client' => $project[1],
                'project_type' => $project[2],
                'progress' => $project[3],
                'target_completion_date' => $project[4],
                'project_month' => $project[5],
                'description' => $project[6],
                'notes' => $project[7],
                'location' => $project[8],
                'latlong' => json_encode(['lat' => $project[9], 'lng' => $project[10]]),
                'sort_order' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

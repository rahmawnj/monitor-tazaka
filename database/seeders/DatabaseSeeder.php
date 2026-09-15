<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            ['Office Network Upgrade','PT Tazaka Teknologi','tazaka_order',75,'2026-10-15','2026-09-01','<p><strong>Network infrastructure upgrade</strong> covering structured cabling, switching, and network optimization.</p><ul><li>Structured cabling installation</li><li>Network switch deployment</li><li>Network performance optimization</li></ul>','<p><strong>Current status:</strong> Main installation is in progress.</p><p>Team is completing the primary network installation and configuration.</p>','Bandung, West Java',-6.9175,107.6191],
            ['CCTV Installation - Warehouse','PT Sinar Logistik','subcontract',50,'2026-10-30','2026-08-01','<p><strong>CCTV installation and monitoring system deployment</strong> for the logistics warehouse.</p><ul><li>Camera installation</li><li>Recording system setup</li><li>Monitoring configuration</li></ul>','<p><strong>Current status:</strong> Camera installation is halfway completed.</p><p>Remaining cameras will be installed in the next installation phase.</p>','Bekasi, West Java',-6.2383,106.9756],
            ['Access Control System','PT Nusantara Properti','external',30,'2026-11-20','2026-09-01','<p><strong>Access control deployment</strong> for office entrances and restricted areas.</p><ul><li>Door access control</li><li>Reader installation</li><li>Access management configuration</li></ul>','<p><strong>Current status:</strong> Hardware procurement is complete.</p><p>Installation and system configuration are ready to proceed.</p>','Jakarta, DKI Jakarta',-6.2088,106.8456],
            ['Fiber Optic Backbone','PT Mitra Data Indonesia','tazaka_order',90,'2026-09-25','2026-07-01','<p><strong>Fiber optic backbone installation</strong> connecting multiple network rooms.</p><ul><li>Fiber optic pulling</li><li>Splicing</li><li>OTDR testing</li></ul>','<p><strong>Current status:</strong> Splicing and final OTDR testing remain.</p><p>The backbone installation is nearing completion.</p>','Surabaya, East Java',-7.2575,112.7521],
            ['Smart Building Monitoring','PT Citra Mandiri','external',15,'2026-12-15','2026-09-01','<p><strong>Centralized monitoring solution</strong> for building systems and operational infrastructure.</p><ul><li>Monitoring dashboard</li><li>System integration</li><li>Building equipment monitoring</li></ul>','<p><strong>Current status:</strong> Initial site survey completed.</p><p>System planning and implementation preparation are underway.</p>','Semarang, Central Java',-6.9667,110.4167],
            ['Data Center CCTV Upgrade','PT Arunika Data Center','tazaka_order',65,'2026-10-10','2026-09-01','<p><strong>CCTV coverage and recording capacity upgrade</strong> for data center areas.</p><ul><li>Camera replacement</li><li>Recording capacity upgrade</li><li>Coverage optimization</li></ul>','<p><strong>Current status:</strong> Camera replacement is underway.</p><p>Replacement work is being completed area by area.</p>','Jakarta, DKI Jakarta',-6.1751,106.8650],
            ['Office WiFi Deployment','PT Karya Digital','subcontract',40,'2026-10-22','2026-09-01','<p><strong>Enterprise WiFi deployment</strong> across three office floors.</p><ul><li>Access point installation</li><li>Network configuration</li><li>Coverage optimization</li></ul>','<p><strong>Current status:</strong> Access point installation has started.</p><p>Installation is progressing across the office floors.</p>','Tangerang, Banten',-6.1783,106.6319],
            ['Gate Barrier Automation','PT Sentosa Industrial','external',80,'2026-09-30','2026-08-01','<p><strong>Automated gate barrier and vehicle access integration.</strong></p><ul><li>Barrier gate installation</li><li>Vehicle access integration</li><li>System testing</li></ul>','<p><strong>Current status:</strong> Integration testing is in progress.</p><p>Access control and barrier integration are being tested.</p>','Karawang, West Java',-6.3227,107.3376],
            ['Warehouse Network Expansion','PT Prima Distribusi','tazaka_order',55,'2026-11-05','2026-09-01','<p><strong>Network expansion</strong> for new warehouse operational zones.</p><ul><li>Network cabling</li><li>Switch deployment</li><li>New operational zone connectivity</li></ul>','<p><strong>Current status:</strong> Cabling materials are on site.</p><p>Installation preparation is complete and cabling work is ready to continue.</p>','Cikarang, West Java',-6.2615,107.1528],
            ['Meeting Room AV System','PT Bintang Konsultan','external',25,'2026-11-12','2026-09-01','<p><strong>Audio visual system installation</strong> for executive meeting rooms.</p><ul><li>Display installation</li><li>Audio system</li><li>Room control integration</li></ul>','<p><strong>Current status:</strong> Equipment selection is finalized.</p><p>Procurement and installation preparation are the next steps.</p>','Jakarta, DKI Jakarta',-6.2297,106.7990],
            ['Fiber Link Metro','PT Lintas Komunikasi','subcontract',70,'2026-10-05','2026-08-01','<p><strong>Metro fiber link deployment</strong> between branch offices.</p><ul><li>Fiber link installation</li><li>Link testing</li><li>Branch connectivity</li></ul>','<p><strong>Current status:</strong> Most links have passed initial testing.</p><p>Remaining links are being finalized and verified.</p>','Depok, West Java',-6.4025,106.7942],
            ['Retail CCTV Rollout','PT Sukses Retail Indonesia','tazaka_order',35,'2026-12-01','2026-09-01','<p><strong>CCTV rollout</strong> across multiple retail locations.</p><ul><li>Camera installation</li><li>Recording system setup</li><li>Store monitoring</li></ul>','<p><strong>Current status:</strong> First batch of stores is underway.</p><p>Additional store locations will follow the rollout schedule.</p>','Bogor, West Java',-6.5950,106.8166],
            ['Server Room Renovation','PT Media Nusantara','external',60,'2026-10-28','2026-08-01','<p><strong>Server room renovation</strong> covering electrical, rack, cooling, and monitoring infrastructure.</p><ul><li>Rack installation</li><li>Electrical improvements</li><li>Cooling system preparation</li><li>Monitoring setup</li></ul>','<p><strong>Current status:</strong> Rack installation is in progress.</p><p>Infrastructure work is continuing according to the renovation plan.</p>','Yogyakarta, DI Yogyakarta',-7.7956,110.3695],
            ['IP Intercom Installation','PT Harmoni Residence','subcontract',45,'2026-11-18','2026-09-01','<p><strong>IP intercom installation</strong> for residential access points.</p><ul><li>Indoor unit installation</li><li>Outdoor station installation</li><li>Network configuration</li></ul>','<p><strong>Current status:</strong> Indoor units are being installed.</p><p>Installation is progressing through the residential access areas.</p>','Bandung, West Java',-6.9147,107.6098],
            ['Smart Parking System','PT Parkir Modern','external',20,'2026-12-20','2026-09-01','<p><strong>Smart parking monitoring and access control system.</strong></p><ul><li>Parking monitoring</li><li>Access control</li><li>System integration</li></ul>','<p><strong>Current status:</strong> System architecture is being prepared.</p><p>The implementation plan and technical configuration are being finalized.</p>','Surabaya, East Java',-7.2459,112.7378],
            ['Campus Network Refresh','Universitas Teknologi Nusantara','tazaka_order',85,'2026-10-02','2026-08-01','<p><strong>Campus network switching and backbone refresh.</strong></p><ul><li>Core switch replacement</li><li>Backbone refresh</li><li>Network configuration</li></ul>','<p><strong>Current status:</strong> Final switch configuration remains.</p><p>Most network infrastructure work has been completed.</p>','Malang, East Java',-7.9666,112.6326],
            ['Hotel Security System','PT Pesona Hospitality','external',50,'2026-11-25','2026-09-01','<p><strong>Integrated CCTV and access control</strong> for hotel facilities.</p><ul><li>CCTV installation</li><li>Access control</li><li>Security system integration</li></ul>','<p><strong>Current status:</strong> Floor-by-floor installation is ongoing.</p><p>Installation is progressing according to the hotel floor schedule.</p>','Bali, Indonesia',-8.6500,115.2167],
            ['Industrial IoT Monitoring','PT Maju Manufaktur','subcontract',10,'2027-01-15','2026-10-01','<p><strong>IoT monitoring solution</strong> for production equipment and utilities.</p><ul><li>IoT device installation</li><li>Equipment monitoring</li><li>Operational dashboard</li></ul>','<p><strong>Current status:</strong> Pilot device installation is planned.</p><p>The pilot phase will validate monitoring and data collection.</p>','Semarang, Central Java',-6.9904,110.4229],
            ['Branch Office Connectivity','PT Satu Jaringan','tazaka_order',95,'2026-09-20','2026-08-01','<p><strong>Connectivity deployment</strong> for branch office network infrastructure.</p><ul><li>Branch network setup</li><li>Connectivity testing</li><li>Documentation and handover</li></ul>','<p><strong>Current status:</strong> Final documentation and handover remain.</p><p>Technical deployment is substantially complete.</p>','Makassar, South Sulawesi',-5.1477,119.4327],
            ['Command Center Display','PT Garda Teknologi','external',32,'2026-12-10','2026-09-01','<p><strong>Large display and monitoring dashboard</strong> for command center operations.</p><ul><li>Display installation</li><li>Dashboard integration</li><li>Monitoring layout</li></ul>','<p><strong>Current status:</strong> Display layout and dashboard integration are in progress.</p><p>The visual layout is being prepared for final system integration.</p>','Bandung, West Java',-6.9175,107.6191],
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

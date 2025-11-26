<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\OpportunityModel;
use App\Models\OpportunityLogModel;

class OpportunitySeeder extends Seeder
{
    public function run()
    {
        $opportunityModel = new OpportunityModel();
        $logModel = new OpportunityLogModel();

        $opportunities = [
            // Opportunities untuk sales01
            [
                'sales_id' => 4, // sales01
                'account_id' => 1, // Bank Central Asia
                'principal_id' => 1, // Microsoft
                'solution_architect_id' => 1, // Rizki Pratama
                'pic' => 'Budi Santoso - IT Manager',
                'project_name' => 'Digital Banking Transformation',
                'license_type_id' => 1, // Enterprise License
                'services_license' => 'Microsoft Azure Cloud Services, Office 365 Enterprise',
                'closing_date' => '2024-03-15',
                'progress' => 'Proposal Stage',
                'opportunity_status' => 'proposal',
                'progress_percentage' => 60,
                'deal_reg' => 'DR-2024-001',
                'estimated_value' => 2500000000,
                'remarks' => 'High priority project for digital transformation'
            ],
            [
                'sales_id' => 4, // sales01
                'account_id' => 2, // Bank Mandiri
                'principal_id' => 2, // Oracle
                'solution_architect_id' => 2, // Sari Indah
                'pic' => 'Sari Dewi - CIO',
                'project_name' => 'Core Banking System Upgrade',
                'license_type_id' => 2, // Standard License
                'services_license' => 'Oracle Database Enterprise Edition, Oracle Fusion Middleware',
                'closing_date' => '2024-02-28',
                'progress' => 'Negotiation',
                'opportunity_status' => 'negotiation',
                'progress_percentage' => 80,
                'deal_reg' => 'DR-2024-002',
                'estimated_value' => 1800000000,
                'remarks' => 'Waiting for final approval from board'
            ],
            [
                'sales_id' => 4, // sales01
                'account_id' => 3, // Bank Rakyat Indonesia
                'principal_id' => 3, // IBM
                'solution_architect_id' => 3, // Fajar Nugroho
                'pic' => 'Ahmad Rizki - Head of IT',
                'project_name' => 'AI-Powered Customer Service',
                'license_type_id' => 3, // Professional License
                'services_license' => 'IBM Watson, IBM Cloud Pak',
                'closing_date' => '2024-04-10',
                'progress' => 'Qualification',
                'opportunity_status' => 'qualification',
                'progress_percentage' => 40,
                'deal_reg' => 'DR-2024-003',
                'estimated_value' => 950000000,
                'remarks' => 'Initial meeting conducted, follow up scheduled'
            ],

            // Opportunities untuk sales02
            [
                'sales_id' => 5, // sales02
                'account_id' => 4, // Bank Negara Indonesia
                'principal_id' => 4, // Cisco
                'solution_architect_id' => 4, // Dewi Lestari
                'pic' => 'Citra Maharani - Network Manager',
                'project_name' => 'Network Infrastructure Upgrade',
                'license_type_id' => 4, // Basic License
                'services_license' => 'Cisco Switches, Routers, Firewalls',
                'closing_date' => '2024-03-20',
                'progress' => 'Introduction',
                'opportunity_status' => 'introduction',
                'progress_percentage' => 20,
                'deal_reg' => 'DR-2024-004',
                'estimated_value' => 750000000,
                'remarks' => 'Initial contact made, needs analysis ongoing'
            ],
            [
                'sales_id' => 5, // sales02
                'account_id' => 5, // Telkom Indonesia
                'principal_id' => 5, // VMware
                'solution_architect_id' => 5, // Bambang Susanto
                'pic' => 'Dian Kusuma - Cloud Architect',
                'project_name' => 'Data Center Virtualization',
                'license_type_id' => 5, // Premium License
                'services_license' => 'VMware vSphere, vCenter, NSX',
                'closing_date' => '2024-02-15',
                'progress' => 'Closed Won',
                'opportunity_status' => 'closed_won',
                'progress_percentage' => 100,
                'deal_reg' => 'DR-2024-005',
                'estimated_value' => 1200000000,
                'remarks' => 'Project successfully closed, implementation starting next month'
            ],

            // Opportunities untuk sales03
            [
                'sales_id' => 6, // sales03
                'account_id' => 6, // Indosat Ooredoo
                'principal_id' => 6, // Dell EMC
                'solution_architect_id' => 6, // Maya Sari
                'pic' => 'Eko Prasetyo - Storage Manager',
                'project_name' => 'Storage Area Network Expansion',
                'license_type_id' => 6, // Trial License
                'services_license' => 'Dell EMC Unity Storage, Data Protection',
                'closing_date' => '2024-03-30',
                'progress' => 'Proposal',
                'opportunity_status' => 'proposal',
                'progress_percentage' => 50,
                'deal_reg' => 'DR-2024-006',
                'estimated_value' => 850000000,
                'remarks' => 'Proposal submitted, waiting for technical evaluation'
            ],
            [
                'sales_id' => 6, // sales03
                'account_id' => 7, // XL Axiata
                'principal_id' => 7, // HP Enterprise
                'solution_architect_id' => 7, // Hendra Gunawan
                'pic' => 'Fajar Abdullah - Server Administrator',
                'project_name' => 'Server Infrastructure Modernization',
                'license_type_id' => 7, // Developer License
                'services_license' => 'HPE ProLiant Servers, HPE OneView',
                'closing_date' => '2024-01-31',
                'progress' => 'Closed Lost',
                'opportunity_status' => 'closed_lost',
                'progress_percentage' => 0,
                'deal_reg' => 'DR-2024-007',
                'estimated_value' => 650000000,
                'remarks' => 'Lost to competitor due to pricing'
            ],

            // Opportunities untuk sales04
            [
                'sales_id' => 7, // sales04
                'account_id' => 8, // PT. Astra International
                'principal_id' => 8, // SAP
                'solution_architect_id' => 8, // Lina Marlina
                'pic' => 'Gita Wulandari - ERP Manager',
                'project_name' => 'ERP System Implementation',
                'license_type_id' => 8, // Academic License
                'services_license' => 'SAP S/4HANA, SAP Fiori',
                'closing_date' => '2024-04-15',
                'progress' => 'Qualification',
                'opportunity_status' => 'qualification',
                'progress_percentage' => 30,
                'deal_reg' => 'DR-2024-008',
                'estimated_value' => 3500000000,
                'remarks' => 'Large project, multiple stakeholders involved'
            ],
            [
                'sales_id' => 7, // sales04
                'account_id' => 9, // PT. Unilever Indonesia
                'principal_id' => 9, // Salesforce
                'solution_architect_id' => 9, // Agus Setiawan
                'pic' => 'Hana Septiani - CRM Manager',
                'project_name' => 'CRM System Upgrade',
                'license_type_id' => 9, // Cloud Subscription
                'services_license' => 'Salesforce Sales Cloud, Service Cloud',
                'closing_date' => '2024-03-05',
                'progress' => 'Negotiation',
                'opportunity_status' => 'negotiation',
                'progress_percentage' => 70,
                'deal_reg' => 'DR-2024-009',
                'estimated_value' => 950000000,
                'remarks' => 'Final pricing negotiation ongoing'
            ]
        ];

        foreach ($opportunities as $opportunity) {
            $opportunityId = $opportunityModel->insert($opportunity);

            // Add log entry for each opportunity
            if ($opportunityId) {
                $logModel->addLog(
                    $opportunityId,
                    $opportunity['sales_id'],
                    'created',
                    null,
                    "Opportunity created: {$opportunity['project_name']}"
                );
            }
        }

        echo "Opportunities seeded successfully!\n";
    }
}

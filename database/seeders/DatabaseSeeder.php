<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin',     'slug' => 'admin'],
            ['name' => 'Evaluator', 'slug' => 'evaluator'],
            ['name' => 'Janitor',   'slug' => 'janitor'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }

        $adminRole = Role::where('slug', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@qmmc.gov.ph'],
            [
                'name'      => 'System Administrator',
                'password'  => Hash::make('Admin@1234'),
                'role_id'   => $adminRole->id,
                'is_active' => true,
            ]
        );

        $areaNames = [
            'ACCOUNTING OFFICE','ADMITTING OFFICE','ADMINISTRATIVE OFFICE',
            'ANESTHESIA OFFICE','ALAGANG PINOY KIOSK','BAC OFFICE',
            'BILLING OFFICE','BUDGET OFFICE','BURN UNIT OFFICE','CMPS OFFICE',
            'COA OFFICE','CTU OFFICE','NDD OFFICE','DIRECTORS OFFICE',
            'EMERGENCY OFFICE','FINANCE OFFICE','HEMODIALYSIS OFFICE',
            'HEPO OFFICE','HIMD OFFICE','HUMAN RESOURCE OFFICE',
            'IMISS OFFICE','ICC OFFICE','LABORATORY OFFICE','LEGAL OFFICE',
            'GSU','MAINTENANCE OFFICE','MICU OFFICE','NURSING OFFICE',
            'OB OFFICE','OPD OFFICE','PEDIA OFFICE','PETRO OFFICE',
            'PHARMACY DEPARTMENT','PHILHEALTH OFFICE','PICU OFFICE',
            'PROCUREMENT OFFICE','REHAB OFFICE','RADIOLOGY OFFICE',
            'QUALITY MANAGEMENT UNIT OFFICE','SUPPLY OFFICE','SURGERY OFFICE',
            'TB DOTS OFFICE','CASHIER','ENDOSCOPY OFFICE','OR OFFICE',
            'PT OFFICE','SICU OFFICE','EHS OFFICE','EEG/EMG','CNS OFFICE',
            'ETHICS OFFICE','DENTAL','HEART STATION','MEDICINE OFFICE',
            'RT OFFICE','FAMILY MEDICINE OFFICE','PHU OFFICE','MSWD',
            'ORL-HNS','NEWBORN HEARING SCREENING','HEMS','WCPU','GAD',
            'PSYCHIATRY AND MENTAL HEALTH','OPHTHALMOLOGY','DATA PRIVACY UNIT',
            'BIOMED','ELECTRICAL','PUBLIC ASSISTANCE AND INFORMATION DESK',
            'DERMATOLOGY','ORTHOPEDICS','ED NURSING','NICU','HMB',
            'CREDIT AND COLLECTION UNIT','MICU Ph9','HFDU','MICU ISO',
            'PERITONEAL DIALYSIS UNIT','DR/OR Complex','AHPS OFFICE',
            'MULTIDISCIPLINARY GARDEN CLINIC',
        ];

        foreach ($areaNames as $name) {
            Area::firstOrCreate(['name' => $name]);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Which department delivers each consultancy service. The consultancy page
 * groups by this, so every active service needs one. Safe to re-run.
 */
class RichServiceDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            // Engineering & Technical
            'civil-engineering-consultancy' => 'CE',
            'structural-design-analysis' => 'CE',
            'architectural-planning-support' => 'CE',
            'construction-management' => 'CE',
            'environmental-engineering' => 'CE',
            'surveying-and-site-investigation' => 'CE',
            'electrical-electronic-engineering' => 'EEE',
            'mechanical-engineering' => 'ME',

            // ICT & Digital
            'software-development' => 'CSE',
            'web-mobile-application-development' => 'CSE',
            'cybersecurity' => 'CSE',
            'data-management' => 'CSE',
            'digital-transformation' => 'CSE',
            'ai-and-emerging-technologies' => 'CSE',

            // Business & Management
            'business-planning' => 'BUS',
            'market-research' => 'BUS',
            'financial-analysis' => 'BUS',
            'hr-consultancy' => 'BUS',
            'entrepreneurship-development' => 'BUS',
            'strategic-management' => 'BUS',
            'organizational-development' => 'BUS',

            // Education & Research — cross-departmental, run by the hub
            'curriculum-development' => 'ENG',
            'educational-assessment' => 'ENG',
            'obe-consultancy' => 'RICH',
            'teacher-training' => 'ENG',
            'research-methodology' => 'RICH',
            'monitoring-evaluation' => 'RICH',
            'institutional-development' => 'RICH',

            // Social Science & Humanities
            'social-research' => 'IS',
            'gender-studies' => 'IS',
            'community-development' => 'PH',
            'communication-studies' => 'ENG',
            'cultural-research' => 'IS',
            'policy-analysis' => 'IS',
            'qualitative-and-quantitative-research' => 'IS',
        ];

        foreach ($departments as $slug => $department) {
            Service::where('slug', $slug)->update(['department' => $department]);
        }
    }
}

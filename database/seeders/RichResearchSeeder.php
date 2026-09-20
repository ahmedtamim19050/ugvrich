<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Research studies for the Research page — the ones still running and the
 * ones finished. Each lines up with a grant or a paper already seeded, so
 * the funded projects, publications and studies tell one story.
 *
 * Leads are named as department teams rather than individuals; put the real
 * investigators in through the admin panel. Safe to re-run.
 */
class RichResearchSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Coastal Water Quality Monitoring Network',
                'slug' => 'coastal-water-quality-monitoring-network',
                'department' => 'CE', 'status' => 'ongoing', 'year' => 2024,
                'summary' => 'A sensor network tracking water quality across coastal districts, funded by the Ministry of Environment.',
                'problem' => 'Coastal communities rely on water sources whose quality is measured rarely and reported late, so contamination is found long after people are affected.',
                'solution' => 'Monitoring stations report salinity, turbidity and contamination indicators continuously, building a public record district by district.',
                'lead_name' => 'Civil Engineering research team',
                'image' => 'projects/community-resilience.jpg',
                'is_featured' => true,
            ],
            [
                'title' => 'Community Resilience in Multi-Hazard Districts',
                'slug' => 'community-resilience-in-multi-hazard-districts',
                'department' => 'PH', 'status' => 'ongoing', 'year' => 2025,
                'summary' => 'A multi-district study of how households prepare for, absorb and recover from repeated climate shocks.',
                'problem' => 'Resilience programmes are designed without evidence on what households in repeatedly affected districts actually do to cope.',
                'solution' => 'Baseline and follow-up surveys across districts, paired with community interviews, measure what changes after each shock.',
                'lead_name' => 'Public Health research team',
                'image' => 'projects/coastal-livelihoods.jpg',
            ],
            [
                'title' => 'Machine Learning for Public Service Prioritisation',
                'slug' => 'machine-learning-for-public-service-prioritisation',
                'department' => 'CSE', 'status' => 'ongoing', 'year' => 2025,
                'summary' => 'Applying machine learning to service request data so district administrations can act on the most urgent cases first.',
                'problem' => 'District offices receive more service requests than they can triage by hand, and urgent cases wait behind routine ones.',
                'solution' => 'Models trained on historical request data rank incoming cases by urgency, with the ranking shown alongside the reasons for it.',
                'lead_name' => 'Computer Science & Engineering research team',
                'image' => 'projects/digital-service.jpg',
            ],
            [
                'title' => 'Seismic Vulnerability of Low-Rise School Buildings',
                'slug' => 'seismic-vulnerability-of-low-rise-school-buildings',
                'department' => 'CE', 'status' => 'completed', 'year' => 2025,
                'summary' => 'An assessment of how low-rise reinforced concrete school buildings in coastal regions perform under earthquake loading.',
                'problem' => 'School buildings across coastal districts were built to varying standards, and their behaviour in an earthquake was unknown.',
                'solution' => 'Field surveys and structural modelling of representative buildings produced a vulnerability classification and a retrofit priority list.',
                'outcome' => 'Published in the Journal of Structural Engineering Research, with a retrofit priority list shared with the district education office.',
                'lead_name' => 'Civil Engineering research team',
                'image' => 'projects/school-building.jpg',
                'is_featured' => true,
            ],
            [
                'title' => 'Entrepreneurial Ecosystems and SME Growth',
                'slug' => 'entrepreneurial-ecosystems-and-sme-growth',
                'department' => 'BUS', 'status' => 'completed', 'year' => 2024,
                'summary' => 'A study of what helps small and medium enterprises grow in secondary cities rather than in the capital.',
                'problem' => 'Enterprise support is designed around capital-city conditions, which do not describe how firms in secondary cities actually grow.',
                'solution' => 'Firm-level interviews and growth data across several secondary cities identified the support that made a measurable difference.',
                'outcome' => 'Published in the South Asian Journal of Business Studies and used in the hub\'s SME advisory work.',
                'lead_name' => 'Business Administration research team',
                'image' => 'projects/coastal-livelihoods.jpg',
            ],
            [
                'title' => 'Assessment Validity under Outcome-Based Education',
                'slug' => 'assessment-validity-under-outcome-based-education',
                'department' => 'RICH', 'status' => 'completed', 'year' => 2024,
                'summary' => 'A multi-programme review of whether assessments under outcome-based education measure the outcomes they claim to.',
                'problem' => 'Programmes adopted outcome-based education quickly, leaving assessments that still measured what the old syllabus rewarded.',
                'solution' => 'Assessment papers across programmes were mapped against stated learning outcomes, and the gaps were traced back to question design.',
                'outcome' => 'Published in Higher Education Quality Review; the mapping method is now used in programme reviews.',
                'lead_name' => 'UGV RICH research team',
                'image' => 'projects/university-classroom.jpg',
            ],
        ];

        foreach ($projects as $i => $data) {
            $project = Project::firstOrNew(['slug' => $data['slug']]);
            $project->fill($data + [
                'type' => 'research',
                'is_featured' => false,
                'sort_order' => $i,
            ]);
            $project->save();
        }
    }
}

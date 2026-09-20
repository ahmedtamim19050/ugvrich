<?php

namespace Database\Seeders;

use App\Models\InnovationArea;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Stat;
use App\Support\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Phase 1 + 2 content: the RICH positioning copy, the KPI dashboard, the
 * Innovation Wing areas and the flagship innovation projects. Safe to re-run.
 */
class RichInnovationSeeder extends Seeder
{
    public function run(): void
    {
        $this->settings();
        $this->pillars();
        $this->kpis();
        $areas = $this->innovationAreas();
        $this->innovationProjects($areas);

        Site::flush();
    }

    protected function settings(): void
    {
        $settings = [
            'site_tagline' => 'Research, Innovation & Consultation Hub',
            'hero_eyebrow' => 'UGV RICH · Research, Innovation & Consultation Hub',
            'hero_heading' => 'Transforming Knowledge into Innovation, Impact and Enterprise',
            'hero_highlight' => 'Innovation',
            'hero_subheading' => 'UGV RICH connects academic research, technological innovation, industry expertise and entrepreneurship to develop practical solutions for society and industry.',

            'about_intro' => 'UGV Research, Innovation & Consultation Hub (RICH) is an interdisciplinary platform of the University of Global Village dedicated to advancing research, innovation, industry collaboration, consultancy and entrepreneurship.',
            'about_body' => 'RICH brings together faculty members, students, researchers, industry professionals and entrepreneurs to identify real-world challenges, develop practical solutions, create prototypes and transform promising innovations into scalable products, services and enterprises.',
            'vision' => 'To become a leading university-based hub for research, innovation, entrepreneurship and industry-driven solutions in Bangladesh.',
            'mission_statement' => 'To foster interdisciplinary research, develop innovative technologies, strengthen university-industry collaboration, support intellectual property creation, and transform research outcomes into solutions with measurable social and economic impact.',
            'mission_intro' => 'Our mission is to:',
            'mission_points' => json_encode([
                'Foster interdisciplinary research.',
                'Develop innovative technologies.',
                'Strengthen university–industry collaboration.',
                'Support intellectual property creation.',
                'Transform research outcomes into solutions with measurable social and economic impact.',
            ]),

            // Manually maintained counts per pipeline stage (edit in Site Settings → Pipeline).
            'pipeline_counts' => json_encode([
                'idea' => 14, 'selected' => 9, 'research' => 7, 'prototype' => 8,
                'testing' => 4, 'patent' => 6, 'incubation' => 3, 'commercialization' => 2,
            ]),
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value,
                'type' => in_array($key, ['mission_points', 'pipeline_counts'], true) ? 'json' : 'text',
                'group' => str_starts_with($key, 'hero_') ? 'hero' : 'general',
            ]);
        }
    }

    /** The four pillars: Research, Innovation, Industry Services, Startup & Commercialization. */
    protected function pillars(): void
    {
        $pillars = [
            'innovation' => [
                'title' => 'Innovation',
                'tagline' => 'Ideas engineered into outcomes',
                'description' => 'The Innovation Wing transforms ideas into practical solutions through interdisciplinary collaboration, prototyping, testing, intellectual property development and commercialization support.',
            ],
            'consultancy' => [
                'title' => 'Industry Services',
                'tagline' => 'From university expertise to industry solutions',
                'icon' => 'briefcase',
                'description' => 'UGV RICH provides research, technical testing, engineering solutions, software development, professional training and consultancy services to industries, government organizations, NGOs and businesses.',
                'items' => ['Research services', 'Technical testing', 'Engineering solutions', 'Software development', 'Professional training', 'Consultancy services'],
            ],
            'hub' => [
                'title' => 'Startup & Commercialization',
                'tagline' => 'From idea to enterprise',
                'icon' => 'rocket',
                'description' => 'Students and faculty take promising ideas from submission through evaluation, mentorship and prototyping to a business model, funding support and the market.',
                'items' => ['Idea submission', 'Evaluation', 'Mentorship', 'Prototype', 'Business model', 'Funding support', 'Startup', 'Market launch'],
            ],
        ];

        foreach ($pillars as $slug => $data) {
            \App\Models\CoreArea::where('slug', $slug)->update(
                collect($data)->map(fn ($v) => is_array($v) ? json_encode($v) : $v)->all()
            );
        }
    }

    protected function kpis(): void
    {
        $kpis = [
            ['Active Projects', '25', 'target'],
            ['Innovations', '18', 'sparkles'],
            ['Publications', '40', 'academic'],
            ['Patents / IP', '6', 'shield'],
            ['Startups', '5', 'rocket'],
            ['Industry Partners', '12', 'handshake'],
        ];

        Stat::query()->delete();

        foreach ($kpis as $i => [$label, $value, $icon]) {
            Stat::create([
                'label' => $label, 'value' => $value, 'suffix' => null,
                'icon' => $icon, 'sort_order' => $i, 'is_active' => true,
            ]);
        }
    }

    protected function innovationAreas(): array
    {
        $areas = [
            ['AI & Software Innovation', 'CSE', 'cpu', 'Intelligent software, connected devices and digital services built by the Department of Computer Science & Engineering.', ['AI', 'IoT', 'Cybersecurity', 'Educational technology', 'Smart applications', 'Automation']],
            ['Electrical & Energy Innovation', 'EEE', 'bolt', 'Clean energy, electric mobility and embedded systems from the Department of Electrical & Electronic Engineering.', ['Solar energy', 'EV technology', 'Smart energy', 'Embedded systems', 'Automation']],
            ['Mechanical & Mobility Innovation', 'ME', 'cog', 'Vehicles, robotics and machinery designed and built by the Department of Mechanical Engineering.', ['Electric vehicles', 'Solar vehicles', 'Robotics', 'Manufacturing technology', 'Agricultural machinery']],
            ['Infrastructure & Environmental Innovation', 'CE', 'building', 'Resilient infrastructure and environmental systems from the Department of Civil Engineering.', ['Smart drainage', 'Sustainable construction', 'Water management', 'Waste management', 'Smart city technology']],
            ['Business & Entrepreneurship Innovation', 'BUS', 'rocket', 'Turning ideas into ventures, with business models and commercialization support from Business Administration.', ['Startup development', 'Business models', 'Digital commerce', 'SME innovation', 'Commercialization']],
            ['Health Innovation', 'PH', 'heart', 'Community and digital health solutions from the Department of Public Health.', ['Digital health', 'Health monitoring', 'Community health solutions', 'Health data systems']],
            ['Language & Education Innovation', 'ENG', 'chat', 'New ways to learn and communicate, from the Department of English.', ['AI-assisted language learning', 'Digital education', 'Communication technology']],
            ['Social & Ethical Innovation', 'IS', 'handshake', 'Value-based and community-led innovation from Islamic Studies and the Humanities.', ['Social innovation', 'Ethics', 'Community development', 'Value-based entrepreneurship']],
        ];

        $models = [];

        foreach ($areas as $i => [$name, $dept, $icon, $description, $focus]) {
            $models[$dept] = InnovationArea::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name, 'department' => $dept, 'icon' => $icon,
                'description' => $description, 'focus' => $focus,
                'sort_order' => $i, 'is_active' => true,
            ]);
        }

        return $models;
    }

    protected function innovationProjects(array $areas): void
    {
        $projects = [
            [
                'title' => 'SUN CAR', 'slug' => 'sun-car', 'department' => 'ME', 'area' => 'ME',
                'stage' => 'patent', 'patent_status' => 'planned', 'commercialization_status' => 'exploring',
                'summary' => "Solar-assisted sustainable mobility innovation developed through UGV's innovation ecosystem.",
                'problem' => 'Conventional vehicles depend on fossil fuel, adding running costs and emissions, while clean mobility options remain expensive.',
                'solution' => 'A solar-assisted vehicle that combines on-board solar generation with electric drive to reduce fuel dependence for everyday mobility.',
                'technologies' => ['Solar PV', 'Electric drivetrain', 'Battery management', 'Lightweight chassis'],
                'team_members' => ['Faculty supervisor — Mechanical Engineering', 'Undergraduate design team', 'Electrical systems mentor — EEE'],
                'research_summary' => 'Bench testing covers panel output across the day, drive efficiency at typical city speeds and the range gained from solar charging. Findings feed a design paper prepared with the Department of Mechanical Engineering.',
                'patent_details' => 'A patent application is being prepared around the vehicle\'s solar charging and drive integration. Until it is filed, technical detail is shared under agreement only.',
                'commercial_potential' => 'Nearest markets are campus and resort shuttles, short-distance delivery and demonstration fleets. The team is in touch with local assemblers about a pilot build.',
                'image' => 'projects/sun-car.jpg',
            ],
            [
                'title' => 'Smart Agriculture System', 'slug' => 'smart-agriculture-system', 'department' => 'CSE', 'area' => 'CSE',
                'stage' => 'testing', 'patent_status' => 'none', 'commercialization_status' => 'exploring',
                'summary' => 'IoT and AI-based monitoring and automated irrigation solutions.',
                'problem' => 'Farmers irrigate by guesswork, wasting water and missing early signs of crop stress.',
                'solution' => 'Field sensors report soil and weather conditions, and an AI model triggers irrigation automatically when crops need it.',
                'technologies' => ['IoT sensors', 'Machine learning', 'Automated irrigation', 'Mobile dashboard'],
                'team_members' => ['Faculty supervisor — Computer Science & Engineering', 'IoT and firmware students', 'Agronomy adviser'],
                'research_summary' => 'Field trials compare sensor-triggered irrigation against the usual schedule, measuring water used and crop condition over a full season. Data is being written up as a journal submission.',
                'patent_details' => 'No patent is planned. The sensor design and irrigation logic are intended for open publication so local growers can build on them.',
                'commercial_potential' => 'A low-cost kit for smallholder farms, sold with seasonal support. Agricultural cooperatives and NGO programmes are the likely first buyers.',
                'image' => 'projects/smart-agriculture.jpg',
            ],
            [
                'title' => 'Smart Drainage & Waterlogging Solution', 'slug' => 'smart-drainage-waterlogging-solution', 'department' => 'CE', 'area' => 'CE',
                'stage' => 'research', 'patent_status' => 'none', 'commercialization_status' => 'none',
                'summary' => 'Sensor-based urban water monitoring and drainage management.',
                'problem' => 'Urban streets waterlog after heavy rain because blocked drains are found only after flooding starts.',
                'solution' => 'Sensors in the drainage network monitor water levels and flow, alerting authorities to blockages before streets flood.',
                'technologies' => ['Water-level sensors', 'IoT network', 'Hydraulic modelling', 'Alert system'],
                'team_members' => ['Faculty supervisor — Civil Engineering', 'Hydraulics research assistants', 'Municipal engineering adviser'],
                'research_summary' => 'Work so far models flow through a section of city drainage and validates sensor placement against recorded flooding. The study is being prepared with the city corporation.',
                'patent_details' => 'No filing at this stage. Any protection would cover the blockage-detection method once field results are complete.',
                'commercial_potential' => 'Sold as a monitoring service to city corporations and municipalities, priced per drainage zone rather than per sensor.',
                'image' => 'projects/smart-drainage.jpg',
            ],
            [
                'title' => 'Automatic Road Cleaning Robot', 'slug' => 'automatic-road-cleaning-robot', 'department' => 'ME', 'area' => 'ME',
                'stage' => 'prototype', 'patent_status' => 'none', 'commercialization_status' => 'none',
                'summary' => 'Technology-driven solution for efficient urban road cleaning.',
                'problem' => 'Manual road cleaning is slow, labour-intensive and exposes workers to traffic and dust.',
                'solution' => 'A robotic cleaner that sweeps and collects road debris along set routes with minimal human supervision.',
                'technologies' => ['Robotics', 'Obstacle sensing', 'Electric drive', 'Route control'],
                'team_members' => ['Faculty supervisor — Mechanical Engineering', 'Robotics and controls students', 'Safety adviser'],
                'research_summary' => 'The prototype is being measured on cleaning coverage, debris collected per run and obstacle handling in traffic conditions.',
                'patent_details' => 'Protection will be considered for the sweeping and collection mechanism once the prototype is stable.',
                'commercial_potential' => 'City cleaning contractors and large private campuses, either as a purchase or as a serviced contract.',
                'image' => 'projects/road-cleaning-robot.jpg',
            ],
            [
                'title' => 'UGV AI Tutor', 'slug' => 'ugv-ai-tutor', 'department' => 'CSE', 'area' => 'CSE',
                'stage' => 'prototype', 'patent_status' => 'none', 'commercialization_status' => 'none',
                'summary' => 'AI-powered academic assistance and personalized learning platform.',
                'problem' => 'Students need help outside class hours, and one-size-fits-all material does not match every learning pace.',
                'solution' => 'An AI tutor that answers course questions, explains concepts step by step and adapts practice to each student.',
                'technologies' => ['Large language models', 'Learning analytics', 'Web platform'],
                'team_members' => ['Faculty supervisor — Computer Science & Engineering', 'Software development students', 'Curriculum adviser — English'],
                'research_summary' => 'A pilot with selected courses tracks whether guided practice improves assessment results, alongside a review of answer accuracy on course material.',
                'patent_details' => 'No patent is planned. The platform is built on published models; the courseware and adaptation logic stay with the university.',
                'commercial_potential' => 'Offered first to UGV students, then licensed to other institutions as a hosted platform with their own course content.',
                'image' => 'projects/ugv-ai-tutor.jpg',
            ],
        ];

        foreach ($projects as $i => $data) {
            $area = $areas[$data['area']] ?? null;
            unset($data['area']);

            $project = Project::firstOrNew(['slug' => $data['slug']]);
            $project->fill($data + [
                'type' => 'innovation',
                'innovation_area_id' => $area?->id,
                'year' => (int) date('Y'),
                'status' => 'ongoing',
                'is_featured' => true,
                'sort_order' => $i,
            ]);
            $project->save();
        }
    }
}

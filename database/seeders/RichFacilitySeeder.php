<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

/**
 * Labs and facilities, one per department plus the shared hub spaces.
 *
 * PLACEHOLDER CONTENT: the equipment lists, locations and photographs are
 * stand-ins (the photos are Unsplash stock of other institutions' labs) and
 * should be replaced in the admin panel with UGV's own.
 * Safe to re-run.
 */
class RichFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Computing & AI Lab',
                'slug' => 'computing-ai-lab',
                'department' => 'CSE',
                'image' => 'facilities/computing-ai-lab.jpg',
                'icon' => 'cpu',
                'description' => 'Workstations, servers and development environments for software, data and machine-learning work.',
                'equipment' => ['GPU workstations', 'Development servers', 'IoT prototyping kits', 'Network test bench'],
                'services' => ['Software development', 'Model training and evaluation', 'Data analysis', 'Student project work'],
            ],
            [
                'name' => 'Electrical & Energy Lab',
                'slug' => 'electrical-energy-lab',
                'department' => 'EEE',
                'image' => 'facilities/electrical-energy-lab.jpg',
                'icon' => 'bolt',
                'description' => 'Power electronics, embedded systems and solar energy testing for clean-energy and automation projects.',
                'equipment' => ['Solar panel test rig', 'Battery cycling equipment', 'Oscilloscopes and power analysers', 'Embedded systems bench'],
                'services' => ['Energy audits', 'Embedded system development', 'Circuit testing', 'Solar performance measurement'],
            ],
            [
                'name' => 'Mechanical Workshop',
                'slug' => 'mechanical-workshop',
                'department' => 'ME',
                'image' => 'facilities/mechanical-workshop.jpg',
                'icon' => 'cog',
                'description' => 'Fabrication and assembly space where vehicles, robots and machinery are built and tested.',
                'equipment' => ['Machining tools', '3D printers', 'Welding and fabrication bay', 'Materials testing equipment'],
                'services' => ['Prototype fabrication', 'Mechanical testing', 'Design and assembly support'],
            ],
            [
                'name' => 'Civil Engineering & Materials Lab',
                'slug' => 'civil-engineering-materials-lab',
                'department' => 'CE',
                'image' => 'facilities/civil-materials-lab.jpg',
                'icon' => 'building',
                'description' => 'Materials and structural testing for construction, infrastructure and environmental assessment work.',
                'equipment' => ['Concrete compression testing machine', 'Soil testing apparatus', 'Survey instruments', 'Water quality test kits'],
                'services' => ['Material testing and certification', 'Structural assessment', 'Site investigation', 'Water quality analysis'],
            ],
            [
                'name' => 'Business Incubation Space',
                'slug' => 'business-incubation-space',
                'department' => 'BUS',
                'image' => 'facilities/business-incubation.jpg',
                'icon' => 'rocket',
                'description' => 'Desks, meeting rooms and mentoring space for student and faculty ventures in incubation.',
                'equipment' => ['Co-working desks', 'Meeting and pitch room', 'Presentation equipment'],
                'services' => ['Startup incubation', 'Mentoring sessions', 'Investor and partner meetings'],
            ],
            [
                'name' => 'Public Health Research Unit',
                'slug' => 'public-health-research-unit',
                'department' => 'PH',
                'image' => 'facilities/public-health-unit.jpg',
                'icon' => 'heart',
                'description' => 'Survey, field research and health data analysis for community health studies.',
                'equipment' => ['Field survey equipment', 'Health monitoring devices', 'Statistical analysis software'],
                'services' => ['Community health studies', 'Survey design and fieldwork', 'Health data analysis'],
            ],
            [
                'name' => 'Language & Digital Learning Studio',
                'slug' => 'language-digital-learning-studio',
                'department' => 'ENG',
                'image' => 'facilities/language-studio.jpg',
                'icon' => 'chat',
                'description' => 'Recording and digital learning space for language teaching, courseware and communication training.',
                'equipment' => ['Recording booth', 'Video and audio equipment', 'Digital learning workstations'],
                'services' => ['Courseware production', 'Language assessment', 'Communication training'],
            ],
            [
                'name' => 'RICH Innovation Hub',
                'slug' => 'rich-innovation-hub',
                'department' => 'RICH',
                'image' => 'facilities/innovation-hub.jpg',
                'icon' => 'lightbulb',
                'description' => 'The shared space where interdisciplinary teams meet, prototype and present their work.',
                'equipment' => ['Project studio', 'Prototyping benches', 'Presentation space'],
                'services' => ['Interdisciplinary project work', 'Workshops and training', 'Demonstrations for partners'],
            ],
        ];

        foreach ($facilities as $i => $data) {
            Facility::updateOrCreate(
                ['slug' => $data['slug']],
                $data + ['sort_order' => $i, 'is_active' => true, 'is_bookable' => true],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\CoreArea;
use App\Models\Expert;
use App\Models\Facility;
use App\Models\Faq;
use App\Models\InnovationArea;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Support\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Writes the Bangla side of the seeded content.
 *
 * Every record already exists in English; this fills the `_bn` column beside
 * each field from database/seeders/data/bangla_content.php. It can be re-run
 * safely, and anything the data file does not mention is left alone — so an
 * untranslated field keeps showing its English text.
 */
class BanglaContentSeeder extends Seeder
{
    /** section in the data file => [model, the column records are found by] */
    private const SECTIONS = [
        'stats' => [Stat::class, 'label'],
        'core_areas' => [CoreArea::class, 'slug'],
        'service_categories' => [ServiceCategory::class, 'slug'],
        'services' => [Service::class, 'slug'],
        'innovation_areas' => [InnovationArea::class, 'slug'],
        'facilities' => [Facility::class, 'slug'],
        'projects' => [Project::class, 'slug'],
        'experts' => [Expert::class, 'slug'],
        'posts' => [Post::class, 'slug'],
        'partners' => [Partner::class, 'name'],
        'testimonials' => [Testimonial::class, 'name'],
        'faqs' => [Faq::class, 'question'],
    ];

    public function run(): void
    {
        $content = require database_path('seeders/data/bangla_content.php');

        $this->settings($content['settings'] ?? []);

        foreach (self::SECTIONS as $section => [$model, $lookup]) {
            $written = 0;

            foreach ($content[$section] ?? [] as $key => $fields) {
                /** @var Model|null $record */
                $record = $model::query()->where($lookup, $key)->first();

                if (! $record) {
                    $this->command?->warn("  {$section}: no record with {$lookup} \"{$key}\"");

                    continue;
                }

                foreach ($fields as $field => $value) {
                    $record->setAttribute($field.'_bn', $value);
                }

                $record->save();
                $written++;
            }

            $this->command?->info(str_pad($section, 20).$written.' translated');
        }

        Site::flush();
    }

    /** Settings keep both languages in one row: `value` and `value_bn`. */
    protected function settings(array $values): void
    {
        $written = 0;

        foreach ($values as $key => $value) {
            $setting = Setting::query()->where('key', $key)->first();

            if (! $setting) {
                $this->command?->warn("  settings: no setting \"{$key}\"");

                continue;
            }

            $setting->value_bn = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $setting->save();
            $written++;
        }

        $this->command?->info(str_pad('settings', 20).$written.' translated');
    }
}

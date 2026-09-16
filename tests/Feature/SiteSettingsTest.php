<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSiteSettings;
use App\Models\Setting;
use App\Models\User;
use App\Support\Site;
use Database\Seeders\RichContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RichContentSeeder::class);
        $this->actingAs(User::factory()->create());
    }

    public function test_it_loads_existing_settings_into_the_form(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->assertSet('data.site_name', 'UGV RICH')
            ->assertSet('data.contact_email', 'rich@ugv.edu.bd');
    }

    public function test_it_saves_plain_text_settings(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->set('data.site_tagline', 'Research & Consultancy Platform')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Research & Consultancy Platform', Setting::where('key', 'site_tagline')->value('value'));
    }

    /** Simple repeaters wrap items as ['value' => ...]; they must save back as a flat JSON list. */
    public function test_it_round_trips_simple_list_settings(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->set('data.mission_points', [
                'item-a' => ['value' => 'First commitment.'],
                'item-b' => ['value' => 'Second commitment.'],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(
            ['First commitment.', 'Second commitment.'],
            json_decode(Setting::where('key', 'mission_points')->value('value'), true),
        );

        // And the public site should read it back as a flat list.
        $this->assertSame(
            ['First commitment.', 'Second commitment.'],
            app(Site::class)->list('mission_points'),
        );
    }

    /** Structured repeaters keep their keys. */
    public function test_it_round_trips_structured_list_settings(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->set('data.why_choose', [
                'item-a' => ['title' => 'Speed', 'description' => 'We move quickly.', 'icon' => 'target'],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(
            [['title' => 'Speed', 'description' => 'We move quickly.', 'icon' => 'target']],
            json_decode(Setting::where('key', 'why_choose')->value('value'), true),
        );
    }

    public function test_saved_settings_reach_the_public_site(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->set('data.hero_heading', 'A brand new heading for the hero')
            ->call('save');

        $this->get(route('home'))->assertOk()->assertSee('A brand new heading for the hero', false);
    }
}

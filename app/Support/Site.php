<?php

namespace App\Support;

use App\Models\ServiceCategory;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Read-side facade over the settings table plus the handful of lookups
 * every page of the public site needs (nav categories, contact block).
 */
class Site
{
    protected array $values;

    protected ?Collection $navCategories = null;

    public function __construct()
    {
        $this->values = Setting::all_cached();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->values[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /** Decode a JSON-valued setting into an array. */
    public function list(string $key): array
    {
        $raw = $this->values[$key] ?? null;

        if (blank($raw)) {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function name(): string
    {
        return (string) $this->get('site_name', 'UGV RICH');
    }

    public function tagline(): string
    {
        return (string) $this->get('site_tagline', 'Research, Innovation & Consultancy Hub');
    }

    /** Memoised per request — models are never put in the cache store. */
    public function navCategories(): Collection
    {
        return $this->navCategories ??= ServiceCategory::active()
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'icon', 'tagline']);
    }

    public function socials(): array
    {
        return collect([
            'facebook' => $this->get('social_facebook'),
            'linkedin' => $this->get('social_linkedin'),
            'x-social' => $this->get('social_x'),
            'youtube' => $this->get('social_youtube'),
        ])->filter()->all();
    }

    /**
     * Clear the cached settings and drop the resolved instance, so anything
     * still holding the container's copy (queue workers, Octane, a request
     * that saves and then renders) picks up the new values.
     */
    public static function flush(): void
    {
        Cache::forget('settings.all');

        app()->forgetInstance(self::class);
    }
}

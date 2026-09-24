<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = [];

    public static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }

    /** Both languages, keyed by setting: ['hero_heading' => ['en' => …, 'bn' => …]]. */
    public static function all_cached(): array
    {
        return Cache::rememberForever('settings.all', fn () => static::query()
            ->get(['key', 'value', 'value_bn'])
            ->mapWithKeys(fn (self $setting) => [$setting->key => [
                'en' => $setting->value,
                'bn' => $setting->value_bn,
            ]])
            ->all());
    }

    /** The value in the language being read, falling back to English. */
    public static function get(string $key, mixed $default = null): mixed
    {
        $values = static::all_cached()[$key] ?? null;

        if ($values === null) {
            return $default;
        }

        $value = app()->getLocale() === 'bn' && filled($values['bn'] ?? null) ? $values['bn'] : $values['en'];

        return ($value === null || $value === '') ? $default : $value;
    }
}

<?php

namespace App\Support;

/**
 * The shared vocabulary in config/rich.php, read in the language of the page.
 *
 * config/rich.php stays the single list of keys — departments, pipeline
 * stages, statuses. lang/{locale}/rich.php holds the wording for each one, so
 * a label a translation has not reached still shows its English text.
 */
class Vocabulary
{
    public static function label(string $group, ?string $key, ?string $default = null): ?string
    {
        if (blank($key)) {
            return $default;
        }

        $line = "rich.{$group}.{$key}";
        $translated = __($line);

        if (is_string($translated) && $translated !== $line) {
            return $translated;
        }

        return config("rich.{$group}.{$key}") ?? $default;
    }

    /** The whole list, in the order config/rich.php gives it. */
    public static function all(string $group): array
    {
        return collect(config("rich.{$group}", []))
            ->map(fn ($label, $key) => static::label($group, $key, $label))
            ->all();
    }
}

<?php

namespace App\Models\Concerns;

/**
 * Reads a model's Bangla twin when the page is being read in Bangla.
 *
 * A model lists its translatable fields in `$translatable`; each one has a
 * `_bn` column beside it. Views ask for `$project->title` as before and get
 * whichever language is being read, falling back to English when the Bangla
 * value has not been written yet.
 */
trait HasTranslations
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (app()->getLocale() !== 'bn' || ! in_array($key, $this->translatable ?? [], true)) {
            return $value;
        }

        $translated = parent::getAttribute($key.'_bn');

        return filled($translated) ? $translated : $value;
    }

    /** The value in one named language, whatever the page is being read in. */
    public function inLocale(string $key, string $locale): mixed
    {
        $value = $locale === 'bn' ? parent::getAttribute($key.'_bn') : parent::getAttribute($key);

        return filled($value) ? $value : parent::getAttribute($key);
    }

    /** @return array<int, string> */
    public function translatableFields(): array
    {
        return $this->translatable ?? [];
    }
}

<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * Every record is written twice: English on one tab, Bangla on the other.
 *
 * The Bangla tab is built from the model's own list of translatable fields, so
 * a field added to a form appears here as soon as the model names it. Bangla is
 * never required — a field left empty falls back to the English text.
 */
class Bilingual
{
    /** Mirrors are kept to inputs that hold plain text. */
    private const MIRRORS = [TextInput::class, Textarea::class, TagsInput::class, RichEditor::class, MarkdownEditor::class];

    /**
     * @param  class-string  $model
     * @param  array<int, Component>  $components
     */
    public static function tabs(string $model, array $components): Tabs
    {
        return Tabs::make('Languages')
            ->contained(false)
            ->columnSpanFull()
            ->tabs([
                Tab::make('English')->schema($components),
                Tab::make('বাংলা')->schema(static::bangla($model, $components)),
            ]);
    }

    /**
     * @param  class-string  $model
     * @param  array<int, Component>  $components
     * @return array<int, Component>
     */
    protected static function bangla(string $model, array $components): array
    {
        $translatable = (new $model)->translatableFields();

        $mirrors = [];

        foreach (static::fields($components) as $field) {
            $name = $field->getName();

            if (in_array($name, $translatable, true) && ! isset($mirrors[$name])) {
                $mirrors[$name] = static::mirror($field);
            }
        }

        // A field the form does not show yet still gets a plain Bangla box.
        foreach ($translatable as $name) {
            $mirrors[$name] ??= TextInput::make($name.'_bn')
                ->label(Str::headline($name))
                ->helperText(static::fallbackNote())
                ->columnSpanFull();
        }

        return array_values($mirrors);
    }

    /**
     * Every field in the tree, sections and groups included.
     *
     * @param  array<int, mixed>|Schema  $components
     * @return array<int, Field>
     */
    protected static function fields(array | Schema $components): array
    {
        if ($components instanceof Schema) {
            $components = $components->getComponents();
        }

        $fields = [];

        foreach ($components as $component) {
            if (! $component instanceof Component) {
                continue;
            }

            if ($component instanceof Field) {
                $fields[] = $component;

                continue;
            }

            $fields = [...$fields, ...static::fields($component->getDefaultChildComponents())];
        }

        return $fields;
    }

    /** The same input again, writing to the `_bn` column beside it. */
    protected static function mirror(Field $field): Field
    {
        $class = in_array($field::class, static::MIRRORS, true) ? $field::class : TextInput::class;

        /** @var Field $mirror */
        $mirror = $class::make($field->getName().'_bn')
            ->label(static::label($field))
            ->helperText(static::fallbackNote())
            ->columnSpanFull();

        if ($mirror instanceof Textarea && $field instanceof Textarea) {
            $mirror->rows($field->getRows() ?? 3);
        }

        if ($mirror instanceof TagsInput) {
            $mirror->placeholder('বাংলায় লিখে Enter চাপুন');
        }

        return $mirror;
    }

    protected static function label(Field $field): string
    {
        try {
            $label = $field->getLabel();
        } catch (\Throwable) {
            $label = null;
        }

        return (string) ($label ?: Str::headline($field->getName()));
    }

    protected static function fallbackNote(): string
    {
        return 'খালি রাখলে ইংরেজি লেখাটিই দেখানো হবে · Leave empty to show the English text.';
    }
}

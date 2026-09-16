<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ServiceCategories\Schemas\ServiceCategoryForm;
use App\Models\Setting;
use App\Support\Site;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSiteSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.manage-site-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|\UnitEnum|null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Site Settings';

    protected static ?string $navigationLabel = 'Site Settings';

    /** Settings stored as JSON arrays rather than plain strings. */
    protected const JSON_KEYS = [
        'mission_points',
        'why_choose',
        'who_we_serve',
        'research_activities',
        'partnership_types',
        'process_steps',
    ];

    /** Of those, the ones whose items are themselves arrays (not plain strings). */
    protected const STRUCTURED_KEYS = ['why_choose', 'process_steps'];

    public ?array $data = [];

    public function mount(): void
    {
        $stored = Setting::query()->pluck('value', 'key')->all();
        $state = [];

        foreach ($stored as $key => $value) {
            if (! in_array($key, self::JSON_KEYS, true)) {
                $state[$key] = $value;

                continue;
            }

            $decoded = json_decode((string) $value, true);
            $decoded = is_array($decoded) ? $decoded : [];

            // Simple repeaters need each item wrapped as ['value' => ...].
            $state[$key] = in_array($key, self::STRUCTURED_KEYS, true)
                ? $decoded
                : array_map(fn ($item) => ['value' => $item], $decoded);
        }

        $this->form->fill($state);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make()->tabs([

                    Tabs\Tab::make('Identity')->icon('heroicon-o-identification')->schema([
                        TextInput::make('site_name')->label('Site name')->required(),
                        TextInput::make('site_tagline')->label('Tagline'),
                        Textarea::make('site_motto')->label('Motto')->rows(2),
                    ]),

                    Tabs\Tab::make('Hero')->icon('heroicon-o-sparkles')->schema([
                        TextInput::make('hero_eyebrow')->label('Eyebrow'),
                        Textarea::make('hero_heading')->label('Heading')->rows(2),
                        TextInput::make('hero_highlight')
                            ->label('Highlighted word')
                            ->helperText('The first occurrence of this word in the heading gets the gradient treatment. Leave empty for none.'),
                        Textarea::make('hero_subheading')->label('Sub-heading')->rows(4),

                        FileUpload::make('hero_video')
                            ->label('Hero video')
                            ->disk('public')
                            ->directory('hero')
                            ->acceptedFileTypes(['video/mp4', 'video/webm'])
                            ->maxSize(20480)
                            ->helperText('MP4 or WebM, 16:9, ideally under 5 MB. Plays muted and looped behind the hero. Leave empty to use the bundled clip.'),

                        FileUpload::make('hero_poster')
                            ->label('Hero poster frame')
                            ->image()
                            ->disk('public')
                            ->directory('hero')
                            ->helperText('Shown while the video loads, and instead of it for visitors who ask for reduced motion.'),
                    ]),

                    Tabs\Tab::make('About')->icon('heroicon-o-information-circle')->schema([
                        Textarea::make('about_intro')->label('Intro paragraph')->rows(4),
                        Textarea::make('about_body')->label('Body paragraph')->rows(6),
                        Textarea::make('vision')->label('Vision statement')->rows(3),

                        FileUpload::make('vision_media')
                            ->label('Vision photo')
                            ->image()
                            ->disk('public')
                            ->directory('about')
                            ->imageEditor()
                            ->helperText('Portrait photo behind the vision statement on the home page. Leave empty to use the bundled campus photo.'),
                        TextInput::make('mission_intro')->label('Mission lead-in'),

                        Repeater::make('mission_points')
                            ->label('Mission points')
                            ->simple(TextInput::make('value')->required())
                            ->reorderable()
                            ->defaultItems(0)
                            ->addActionLabel('Add mission point'),
                    ]),

                    Tabs\Tab::make('Why choose us')->icon('heroicon-o-star')->schema([
                        Repeater::make('why_choose')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('title')->required(),
                                TextInput::make('description')->required()->columnSpan(2),
                                Select::make('icon')
                                    ->options(ServiceCategoryForm::ICONS)
                                    ->native(false),
                            ])
                            ->columns(4)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['title'] ?? null)
                            ->defaultItems(0)
                            ->addActionLabel('Add reason'),
                    ]),

                    Tabs\Tab::make('Audience')->icon('heroicon-o-user-group')->schema([
                        Repeater::make('who_we_serve')
                            ->label('Who we serve')
                            ->simple(TextInput::make('value')->required())
                            ->reorderable()
                            ->defaultItems(0)
                            ->addActionLabel('Add audience'),

                        Repeater::make('partnership_types')
                            ->label('Partnership types')
                            ->simple(TextInput::make('value')->required())
                            ->reorderable()
                            ->defaultItems(0)
                            ->addActionLabel('Add partnership type'),
                    ]),

                    Tabs\Tab::make('Research')->icon('heroicon-o-beaker')->schema([
                        Repeater::make('research_activities')
                            ->label('Research & innovation activities')
                            ->simple(TextInput::make('value')->required())
                            ->reorderable()
                            ->defaultItems(0)
                            ->addActionLabel('Add activity'),
                    ]),

                    Tabs\Tab::make('Process')->icon('heroicon-o-map')->schema([
                        Repeater::make('process_steps')
                            ->label('How we work')
                            ->schema([
                                TextInput::make('title')->required(),
                                Textarea::make('description')->required()->rows(3),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['title'] ?? null)
                            ->defaultItems(0)
                            ->addActionLabel('Add step'),
                    ]),

                    Tabs\Tab::make('Contact')->icon('heroicon-o-phone')->schema([
                        Section::make('Office')->columns(2)->schema([
                            Textarea::make('contact_address')->rows(2)->columnSpanFull(),
                            TextInput::make('contact_email')->email(),
                            TextInput::make('contact_phone')->tel(),
                            TextInput::make('contact_website')->url(),
                            TextInput::make('contact_hours')->label('Office hours'),
                        ]),

                        Section::make('Social')->columns(2)->schema([
                            TextInput::make('social_facebook')->label('Facebook')->url(),
                            TextInput::make('social_linkedin')->label('LinkedIn')->url(),
                            TextInput::make('social_x')->label('X')->url(),
                            TextInput::make('social_youtube')->label('YouTube')->url(),
                        ]),
                    ]),
                ])->contained(false),
            ]);
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            $isJson = in_array($key, self::JSON_KEYS, true);

            if ($isJson) {
                $value = is_array($value) ? $value : [];

                // Unwrap simple repeaters back into a flat list of strings.
                if (! in_array($key, self::STRUCTURED_KEYS, true)) {
                    $value = array_map(
                        fn ($item) => is_array($item) ? ($item['value'] ?? '') : $item,
                        $value,
                    );
                }

                $value = json_encode(array_values($value));
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $isJson ? 'json' : 'text',
                    'group' => match (true) {
                        str_starts_with($key, 'contact_') => 'contact',
                        str_starts_with($key, 'social_') => 'social',
                        str_starts_with($key, 'hero_') => 'hero',
                        default => 'general',
                    },
                ],
            );
        }

        Site::flush();

        Notification::make()
            ->title('Site settings saved')
            ->body('The public site has been updated.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save changes')
                ->action('save')
                ->keyBindings(['mod+s']),

            Action::make('view_site')
                ->label('View site')
                ->url(url('/'), shouldOpenInNewTab: true)
                ->color('gray')
                ->icon('heroicon-o-arrow-top-right-on-square'),
        ];
    }
}

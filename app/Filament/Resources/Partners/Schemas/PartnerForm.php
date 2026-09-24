<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\Partner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Bilingual::tabs(Partner::class, [
                    Section::make('Partner')
                        ->columns(2)
                        ->schema([
                            TextInput::make('name')->required()->maxLength(180)->columnSpanFull(),

                            Select::make('type')
                                ->native(false)
                                ->options([
                                    'University' => 'University',
                                    'Research institution' => 'Research institution',
                                    'Government agency' => 'Government agency',
                                    'Industry' => 'Industry',
                                    'NGO' => 'NGO',
                                    'Development partner' => 'Development partner',
                                    'International organization' => 'International organization',
                                ]),

                            TextInput::make('website')->url()->maxLength(255),

                            FileUpload::make('logo')
                                ->image()
                                ->disk('public')
                                ->directory('partners')
                                ->helperText('Transparent PNG or SVG works best.')
                                ->columnSpanFull(),

                            TextInput::make('sort_order')->numeric()->default(0)->required(),

                            Toggle::make('is_active')->label('Visible on the site')->default(true),
                        ]),
                ]),
            ]);
    }
}

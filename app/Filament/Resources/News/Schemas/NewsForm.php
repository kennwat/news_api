<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content')
                    ->schema([
                        TextInput::make('title.en')
                            ->label('Title (English)')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, $set, ?string $state) {
                                if (! $get('slug') && $state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('title.de')
                            ->label('Title (German)')
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),

                        Textarea::make('short_description.en')
                            ->label('Short Description (English)')
                            ->required()
                            ->rows(3)
                            ->maxLength(500),

                        Textarea::make('short_description.de')
                            ->label('Short Description (German)')
                            ->rows(3)
                            ->maxLength(500),
                    ])
                    ->columnSpanFull(),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('image_preview_path')
                            ->label('Preview Image')
                            ->image()
                            ->disk('public')
                            ->directory('news/previews')
                            ->imageEditor()
                            ->maxSize(5120),
                    ])
                    ->columnSpanFull(),

                Section::make('Publishing')
                    ->schema([
                        Select::make('user_id')
                            ->label('Author')
                            ->relationship('author', 'name')
                            ->required()
                            ->default(auth()->id())
                            ->searchable(),

                        DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->seconds(false),

                        Toggle::make('is_visible')
                            ->label('Visible')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(3),
            ]);
    }
}

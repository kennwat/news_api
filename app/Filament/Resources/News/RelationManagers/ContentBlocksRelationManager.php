<?php

namespace App\Filament\Resources\News\RelationManagers;

use App\Enums\BlockTypeEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContentBlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'contentBlocks';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Block Configuration')
                    ->schema([
                        Select::make('type')
                            ->label('Block Type')
                            ->options([
                                BlockTypeEnum::Text->value => 'Text',
                                BlockTypeEnum::Image->value => 'Image',
                                BlockTypeEnum::TextImageRight->value => 'Text with Image (Right)',
                                BlockTypeEnum::TextImageLeft->value => 'Text with Image (Left)',
                                BlockTypeEnum::Slider->value => 'Slider',
                            ])
                            ->required()
                            ->native(false)
                            ->live(),

                        TextInput::make('position')
                            ->label('Position')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Block Details')
                    ->schema([
                        Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                RichEditor::make('text_content.en')
                                    ->label('Text Content (English)')
                                    ->columnSpanFull(),

                                RichEditor::make('text_content.de')
                                    ->label('Text Content (German)')
                                    ->columnSpanFull(),

                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('news/blocks')
                                    ->imageEditor()
                                    ->maxSize(5120),

                                TextInput::make('image_alt_text.en')
                                    ->label('Image Alt Text (English)')
                                    ->maxLength(255),

                                TextInput::make('image_alt_text.de')
                                    ->label('Image Alt Text (German)')
                                    ->maxLength(255),

                                TextInput::make('position')
                                    ->label('Position')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->orderColumn('position')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['text_content']['en'] ?? $state['image_alt_text']['en'] ?? null)
                            ->defaultItems(1),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Position')
                    ->sortable()
                    ->badge(),

                TextColumn::make('details_count')
                    ->label('Details')
                    ->counts('details')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('position', 'asc')
            ->reorderable('position');
    }
}

<?php

namespace App\Filament\Resources\CarImages;

use App\Filament\Resources\CarImages\Pages\ManageCarImages;
use App\Models\CarImage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CarImageResource extends Resource
{
    protected static ?string $model = CarImage::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static \UnitEnum|string|null $navigationGroup = 'E\'lonlar';

    protected static ?string $modelLabel = 'E\'lon rasmi';

    protected static ?string $pluralModelLabel = 'E\'lon rasmlari';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('car_id')
                    ->label('E\'lon ID')
                    ->numeric()
                    ->required(),
                TextInput::make('user_id')
                    ->label('Foydalanuvchi ID')
                    ->numeric()
                    ->required(),
                TextInput::make('image_key')
                    ->label('Rasm kaliti')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label('Tartib')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('car.marka')
                    ->label('E\'lon')
                    ->formatStateUsing(fn ($record) => $record->car?->marka . ' ' . $record->car?->model),
                TextColumn::make('user.name')
                    ->label('Foydalanuvchi'),
                TextColumn::make('image_key')
                    ->label('Rasm')
                    ->limit(30),
                TextColumn::make('sort_order')
                    ->label('Tartib')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCarImages::route('/'),
        ];
    }
}

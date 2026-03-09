<?php

namespace App\Filament\Resources\OtpCodes;

use App\Filament\Resources\OtpCodes\Pages\ManageOtpCodes;
use App\Models\OtpCode;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class OtpCodeResource extends Resource
{
    protected static ?string $model = OtpCode::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedKey;

    protected static \UnitEnum|string|null $navigationGroup = 'Boshqaruv';

    protected static ?string $modelLabel = 'OTP kod';

    protected static ?string $pluralModelLabel = 'OTP kodlar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')
                    ->label('Telefon')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Kod')
                    ->required()
                    ->maxLength(4),
                Toggle::make('used')
                    ->label('Ishlatilgan')
                    ->default(false),
                DateTimePicker::make('expires_at')
                    ->label('Muddati')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Kod')
                    ->searchable(),
                IconColumn::make('used')
                    ->label('Ishlatilgan')
                    ->boolean(),
                TextColumn::make('expires_at')
                    ->label('Muddati')
                    ->dateTime()
                    ->sortable()
                    ->color(fn (OtpCode $record) => $record->isExpired() ? 'danger' : 'success'),
                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('used')
                    ->label('Ishlatilgan')
                    ->options([
                        true => 'Ha',
                        false => 'Yo\'q',
                    ]),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOtpCodes::route('/'),
        ];
    }
}

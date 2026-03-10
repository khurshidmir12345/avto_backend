<?php

namespace App\Filament\Resources\Advertisements;

use App\Filament\Resources\Advertisements\Pages\ManageAdvertisements;
use App\Models\Advertisement;
use App\Services\AdvertisementService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static \UnitEnum|string|null $navigationGroup = 'Reklamalar';

    protected static ?string $modelLabel = 'Reklama';

    protected static ?string $pluralModelLabel = 'Reklamalar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Foydalanuvchi')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->disabled(),
                TextInput::make('title')
                    ->label('Sarlavha')
                    ->required()
                    ->maxLength(150),
                Textarea::make('description')
                    ->label('Tavsif')
                    ->maxLength(1000),
                TextInput::make('image_key')
                    ->label('Rasm kaliti')
                    ->disabled(),
                TextInput::make('link')
                    ->label('Havola')
                    ->url()
                    ->maxLength(500),
                Select::make('status')
                    ->label('Holati')
                    ->options([
                        'pending' => 'Kutilmoqda',
                        'approved' => 'Tasdiqlangan',
                        'rejected' => 'Rad etilgan',
                        'expired' => 'Muddati tugagan',
                        'draft' => 'Qoralama',
                    ])
                    ->disabled(),
                TextInput::make('days')
                    ->label('Kunlar soni')
                    ->numeric()
                    ->disabled(),
                TextInput::make('total_price')
                    ->label('Umumiy narx')
                    ->numeric()
                    ->disabled()
                    ->formatStateUsing(fn ($state) => number_format((int) $state) . ' UZS'),
                TextInput::make('views')
                    ->label('Ko\'rishlar')
                    ->numeric()
                    ->disabled(),
                Textarea::make('rejection_reason')
                    ->label('Rad etish sababi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Foydalanuvchi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Sarlavha')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('days')
                    ->label('Kun')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Narx')
                    ->formatStateUsing(fn ($state) => number_format((int) $state) . ' UZS')
                    ->sortable(),
                TextColumn::make('views')
                    ->label('Ko\'rishlar')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Holati')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Kutilmoqda',
                        'approved' => 'Faol',
                        'rejected' => 'Rad etilgan',
                        'expired' => 'Tugagan',
                        'draft' => 'Qoralama',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'expired' => 'gray',
                        'draft' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('expires_at')
                    ->label('Tugash vaqti')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Holati')
                    ->options([
                        'pending' => 'Kutilmoqda',
                        'approved' => 'Faol',
                        'rejected' => 'Rad etilgan',
                        'expired' => 'Tugagan',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Tasdiqlash')
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheck)
                    ->visible(fn (Advertisement $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Reklamani tasdiqlash')
                    ->modalDescription('Bu reklama faollashtiriladi va foydalanuvchilarga ko\'rsatiladi.')
                    ->action(function (Advertisement $record) {
                        $admin = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
                        app(AdvertisementService::class)->approve($record, $admin);
                    }),
                Action::make('reject')
                    ->label('Rad etish')
                    ->color('danger')
                    ->icon(Heroicon::OutlinedXMark)
                    ->visible(fn (Advertisement $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Reklamani rad etish')
                    ->modalDescription('Reklama rad etiladi va foydalanuvchiga pul qaytariladi.')
                    ->form([
                        Textarea::make('reason')
                            ->label('Sabab')
                            ->placeholder('Rad etish sababini kiriting...')
                            ->maxLength(500),
                    ])
                    ->action(function (Advertisement $record, array $data) {
                        app(AdvertisementService::class)->reject($record, $data['reason'] ?? null);
                    }),
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
            'index' => ManageAdvertisements::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use App\Services\BalanceService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static \UnitEnum|string|null $navigationGroup = 'Boshqaruv';

    protected static ?string $modelLabel = 'Foydalanuvchi';

    protected static ?string $pluralModelLabel = 'Foydalanuvchilar';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Ism')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefon')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Parol')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
                Toggle::make('is_admin')
                    ->label('Admin')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Ism')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('balance')
                    ->label('Balans')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => number_format($state) . ' UZS'),
                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean(),
                IconColumn::make('is_banned')
                    ->label('Ban')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success'),
                TextColumn::make('created_at')
                    ->label('Ro\'yxatdan o\'tgan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\Action::make('addBalance')
                    ->label('Balans')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->form([
                        TextInput::make('amount')
                            ->label('Summa (UZS)')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        TextInput::make('description')
                            ->label('Izoh')
                            ->default('Admin tomonidan qo\'shildi')
                            ->maxLength(255),
                    ])
                    ->action(function (User $record, array $data): void {
                        app(BalanceService::class)->addCredit(
                            $record,
                            (int) $data['amount'],
                            $data['description'] ?? 'Admin tomonidan qo\'shildi'
                        );
                        Notification::make()
                            ->title('Balans qo\'shildi')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('changePassword')
                    ->label('Parol')
                    ->icon(Heroicon::OutlinedKey)
                    ->form([
                        TextInput::make('new_password')
                            ->label('Yangi parol')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->confirmed(),
                        TextInput::make('new_password_confirmation')
                            ->label('Parolni tasdiqlang')
                            ->password()
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update(['password' => $data['new_password']]);
                        Notification::make()
                            ->title('Parol o\'zgartirildi')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('banUser')
                    ->label('Ban')
                    ->icon(Heroicon::OutlinedNoSymbol)
                    ->color('danger')
                    ->visible(fn (User $record) => !$record->is_banned)
                    ->requiresConfirmation()
                    ->form([
                        TextInput::make('ban_reason')
                            ->label('Ban sababi')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'is_banned' => true,
                            'banned_at' => now(),
                            'ban_reason' => $data['ban_reason'],
                        ]);
                        $record->tokens()->delete();
                        Notification::make()->title('Foydalanuvchi ban qilindi')->success()->send();
                    }),
                \Filament\Actions\Action::make('unbanUser')
                    ->label('Unban')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->visible(fn (User $record) => $record->is_banned)
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $record->update([
                            'is_banned' => false,
                            'banned_at' => null,
                            'ban_reason' => null,
                        ]);
                        Notification::make()->title('Foydalanuvchi ban olib tashlandi')->success()->send();
                    }),
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
            'index' => ManageUsers::route('/'),
        ];
    }
}

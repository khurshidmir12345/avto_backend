<?php

namespace App\Filament\Resources\Reports;

use App\Filament\Resources\Reports\Pages\ManageReports;
use App\Models\MoshinaElon;
use App\Models\Report;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static \UnitEnum|string|null $navigationGroup = 'Moderatsiya';

    protected static ?string $modelLabel = 'Shikoyat';

    protected static ?string $pluralModelLabel = 'Shikoyatlar';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = Report::where('status', Report::STATUS_PENDING)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('reporter.name')
                    ->label('Shikoyatchi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reportable_type')
                    ->label('Turi')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'App\Models\MoshinaElon' => "E'lon",
                        'App\Models\User' => 'Foydalanuvchi',
                        'App\Models\Message' => 'Xabar',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'App\Models\MoshinaElon' => 'info',
                        'App\Models\User' => 'warning',
                        'App\Models\Message' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('reportable_id')
                    ->label('Kontent ID'),
                TextColumn::make('reason')
                    ->label('Sabab')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'spam' => 'Spam',
                        'inappropriate' => "Noto'g'ri kontent",
                        'fraud' => 'Firibgarlik',
                        'offensive' => 'Haqoratli',
                        'other' => 'Boshqa',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'spam' => 'warning',
                        'inappropriate' => 'danger',
                        'fraud' => 'danger',
                        'offensive' => 'danger',
                        'other' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Tavsif')
                    ->limit(50)
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->label('Holat')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Kutilmoqda',
                        'reviewed' => "Ko'rib chiqilmoqda",
                        'resolved' => 'Hal qilindi',
                        'dismissed' => 'Rad etildi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'resolved' => 'success',
                        'dismissed' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Sana')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Holat')
                    ->options([
                        'pending' => 'Kutilmoqda',
                        'reviewed' => "Ko'rib chiqilmoqda",
                        'resolved' => 'Hal qilindi',
                        'dismissed' => 'Rad etildi',
                    ]),
                SelectFilter::make('reason')
                    ->label('Sabab')
                    ->options([
                        'spam' => 'Spam',
                        'inappropriate' => "Noto'g'ri kontent",
                        'fraud' => 'Firibgarlik',
                        'offensive' => 'Haqoratli',
                        'other' => 'Boshqa',
                    ]),
                SelectFilter::make('reportable_type')
                    ->label('Turi')
                    ->options([
                        'App\Models\MoshinaElon' => "E'lon",
                        'App\Models\User' => 'Foydalanuvchi',
                        'App\Models\Message' => 'Xabar',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('review')
                    ->label("Ko'rish")
                    ->icon(Heroicon::OutlinedEye)
                    ->color('info')
                    ->visible(fn (Report $record) => $record->status === Report::STATUS_PENDING)
                    ->action(function (Report $record): void {
                        $record->update([
                            'status' => Report::STATUS_REVIEWED,
                        ]);
                        Notification::make()->title("Ko'rib chiqilmoqda")->success()->send();
                    }),
                \Filament\Actions\Action::make('resolve')
                    ->label('Hal qilish')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->visible(fn (Report $record) => in_array($record->status, [Report::STATUS_PENDING, Report::STATUS_REVIEWED]))
                    ->form([
                        Textarea::make('admin_note')
                            ->label('Admin izohi')
                            ->maxLength(1000),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $record->update([
                            'status' => Report::STATUS_RESOLVED,
                            'admin_note' => $data['admin_note'] ?? null,
                            'resolved_by' => auth()->id(),
                            'resolved_at' => now(),
                        ]);
                        Notification::make()->title('Shikoyat hal qilindi')->success()->send();
                    }),
                \Filament\Actions\Action::make('dismiss')
                    ->label('Rad etish')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('gray')
                    ->visible(fn (Report $record) => in_array($record->status, [Report::STATUS_PENDING, Report::STATUS_REVIEWED]))
                    ->form([
                        Textarea::make('admin_note')
                            ->label('Admin izohi')
                            ->maxLength(1000),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $record->update([
                            'status' => Report::STATUS_DISMISSED,
                            'admin_note' => $data['admin_note'] ?? null,
                            'resolved_by' => auth()->id(),
                            'resolved_at' => now(),
                        ]);
                        Notification::make()->title('Shikoyat rad etildi')->info()->send();
                    }),
                \Filament\Actions\Action::make('banUser')
                    ->label('Ban qilish')
                    ->icon(Heroicon::OutlinedNoSymbol)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Textarea::make('ban_reason')
                            ->label('Ban sababi')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->action(function (Report $record, array $data): void {
                        $user = null;
                        if ($record->reportable_type === User::class) {
                            $user = User::find($record->reportable_id);
                        } elseif ($record->reportable_type === MoshinaElon::class) {
                            $elon = MoshinaElon::find($record->reportable_id);
                            $user = $elon?->user;
                        }

                        if ($user) {
                            $user->update([
                                'is_banned' => true,
                                'banned_at' => now(),
                                'ban_reason' => $data['ban_reason'],
                            ]);
                            $user->tokens()->delete();
                            $record->update([
                                'status' => Report::STATUS_RESOLVED,
                                'admin_note' => 'Foydalanuvchi ban qilindi: ' . $data['ban_reason'],
                                'resolved_by' => auth()->id(),
                                'resolved_at' => now(),
                            ]);
                            Notification::make()->title('Foydalanuvchi ban qilindi')->success()->send();
                        } else {
                            Notification::make()->title('Foydalanuvchi topilmadi')->danger()->send();
                        }
                    }),
                \Filament\Actions\Action::make('deleteContent')
                    ->label("Kontentni o'chirish")
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Report $record) => $record->reportable_type === MoshinaElon::class)
                    ->action(function (Report $record): void {
                        $elon = MoshinaElon::find($record->reportable_id);
                        if ($elon) {
                            $elon->update(['holati' => 'inactive']);
                            $record->update([
                                'status' => Report::STATUS_RESOLVED,
                                'admin_note' => "E'lon nofaol qilindi",
                                'resolved_by' => auth()->id(),
                                'resolved_at' => now(),
                            ]);
                            Notification::make()->title("E'lon nofaol qilindi")->success()->send();
                        }
                    }),
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
            'index' => ManageReports::route('/'),
        ];
    }
}

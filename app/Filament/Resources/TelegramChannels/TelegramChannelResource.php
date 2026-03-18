<?php

namespace App\Filament\Resources\TelegramChannels;

use App\Filament\Resources\TelegramChannels\Pages\ManageTelegramChannels;
use App\Models\TelegramChannel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class TelegramChannelResource extends Resource
{
    protected static ?string $model = TelegramChannel::class;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static \UnitEnum|string|null $navigationGroup = 'Sozlamalar';

    protected static ?string $modelLabel = 'Global kanal';

    protected static ?string $pluralModelLabel = 'Global kanallar';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Kanal nomi')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Avto Vodiy Farg\'ona'),
                TextInput::make('username')
                    ->label('Username')
                    ->maxLength(255)
                    ->placeholder('avtovodiyfergana')
                    ->helperText('@ belgisisiz yozing'),
                TextInput::make('link')
                    ->label('Havola')
                    ->required()
                    ->url()
                    ->maxLength(500)
                    ->placeholder('https://t.me/avtovodiyfergana'),
                Textarea::make('description')
                    ->label('Tavsif')
                    ->maxLength(1000)
                    ->rows(3),
                FileUpload::make('avatar_path')
                    ->label('Kanal rasmi')
                    ->image()
                    ->directory('telegram-channels')
                    ->disk(config('moshina_elon.images.disk', 'r2'))
                    ->maxSize(5120)
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('400')
                    ->imageResizeTargetHeight('400')
                    ->fetchFileInformation(false)
                    ->getUploadedFileUsing(function (BaseFileUpload $component, string $file): ?array {
                        $disk = $component->getDiskName();
                        $url = Storage::disk($disk)->url($file);
                        return [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => 'image/jpeg',
                            'url' => $url,
                        ];
                    })
                    ->saveUploadedFileUsing(function (BaseFileUpload $component, $file) {
                        $disk = $component->getDiskName();
                        $directory = $component->getDirectory();
                        $path = $file->store($directory, $disk);
                        return $path;
                    })
                    ->columnSpanFull(),
                TextInput::make('member_count')
                    ->label('A\'zolar soni')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                TextInput::make('sort_order')
                    ->label('Tartib raqami')
                    ->numeric()
                    ->default(0)
                    ->helperText('Kichik son = yuqorida ko\'rinadi'),
                Toggle::make('is_active')
                    ->label('Faol')
                    ->default(true)
                    ->helperText('Ilovada ko\'rinishi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width('60px'),
                ImageColumn::make('avatar_url')
                    ->label('Rasm')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=T&background=0088CC&color=fff')
                    ->width(40)
                    ->height(40),
                TextColumn::make('name')
                    ->label('Kanal nomi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => $state ? "@{$state}" : '—')
                    ->color('primary'),
                TextColumn::make('member_count')
                    ->label('A\'zolar')
                    ->sortable()
                    ->numeric()
                    ->formatStateUsing(fn (int $state): string => number_format($state)),
                TextColumn::make('link')
                    ->label('Havola')
                    ->limit(30)
                    ->url(fn (TelegramChannel $record): string => $record->link)
                    ->openUrlInNewTab()
                    ->color('info'),
                IconColumn::make('is_active')
                    ->label('Faol')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Tartib')
                    ->sortable()
                    ->width('70px'),
                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Holati')
                    ->trueLabel('Faol')
                    ->falseLabel('Nofaol')
                    ->placeholder('Barchasi'),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if (!empty($data['avatar_path'])) {
                            $data['avatar_disk'] = config('moshina_elon.images.disk', 'r2');
                        }
                        return $data;
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
            'index' => ManageTelegramChannels::route('/'),
        ];
    }
}

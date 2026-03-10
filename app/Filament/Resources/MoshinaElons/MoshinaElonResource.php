<?php

namespace App\Filament\Resources\MoshinaElons;

use App\Filament\Resources\MoshinaElons\Pages\EditMoshinaElon;
use App\Filament\Resources\MoshinaElons\Pages\ManageMoshinaElons;
use App\Models\Category;
use App\Models\MoshinaElon;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MoshinaElonResource extends Resource
{
    protected static ?string $model = MoshinaElon::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static \UnitEnum|string|null $navigationGroup = 'E\'lonlar';

    protected static ?string $modelLabel = 'Moshina e\'loni';

    protected static ?string $pluralModelLabel = 'Moshina e\'lonlari';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Foydalanuvchi')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Select::make('category_id')
                    ->label('Kategoriya')
                    ->options(Category::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('marka')
                    ->label('Marka')
                    ->required()
                    ->maxLength(100),
                TextInput::make('model')
                    ->label('Model')
                    ->required()
                    ->maxLength(100),
                TextInput::make('yil')
                    ->label('Yil')
                    ->numeric()
                    ->required(),
                TextInput::make('probeg')
                    ->label('Probeg (km)')
                    ->numeric(),
                TextInput::make('narx')
                    ->label('Narx')
                    ->numeric()
                    ->required(),
                Select::make('valyuta')
                    ->label('Valyuta')
                    ->options(['USD' => 'USD', 'UZS' => 'UZS'])
                    ->default('UZS'),
                TextInput::make('rang')
                    ->label('Rang')
                    ->maxLength(50),
                Select::make('yoqilgi_turi')
                    ->label('Yoqilg\'i turi')
                    ->options(config('moshina_elon.yoqilgi_turlari', [])),
                Select::make('uzatish_qutisi')
                    ->label('Uzatish qutisi')
                    ->options(array_combine(config('moshina_elon.uzatish_qutisi_turlari', []), config('moshina_elon.uzatish_qutisi_turlari', []))),
                TextInput::make('kraska_holati')
                    ->label('Kraska holati')
                    ->maxLength(255),
                TextInput::make('shahar')
                    ->label('Shahar')
                    ->maxLength(100),
                TextInput::make('telefon')
                    ->label('Telefon')
                    ->tel()
                    ->maxLength(20),
                Textarea::make('tavsif')
                    ->label('Tavsif')
                    ->maxLength(5000)
                    ->columnSpanFull(),
                Select::make('holati')
                    ->label('Holati')
                    ->options([
                        'active' => 'Faol',
                        'sold' => 'Sotilgan',
                        'inactive' => 'Nofaol',
                    ])
                    ->default('active'),
                Checkbox::make('bank_kredit')
                    ->label('Bank kredit'),
                Checkbox::make('general')
                    ->label('General'),
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
                TextColumn::make('marka')
                    ->label('Marka')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model')
                    ->label('Model')
                    ->searchable(),
                TextColumn::make('yil')
                    ->label('Yil')
                    ->sortable(),
                TextColumn::make('narx')
                    ->label('Narx')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format((float) $state)),
                TextColumn::make('holati')
                    ->label('Holati')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Faol',
                        'sold' => 'Sotilgan',
                        'inactive' => 'Nofaol',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'sold' => 'gray',
                        'inactive' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('holati')
                    ->label('Holati')
                    ->options([
                        'active' => 'Faol',
                        'sold' => 'Sotilgan',
                        'inactive' => 'Nofaol',
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMoshinaElons::route('/'),
            'edit' => EditMoshinaElon::route('/{record}/edit'),
        ];
    }
}

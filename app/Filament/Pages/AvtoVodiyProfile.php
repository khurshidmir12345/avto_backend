<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AvtoVodiyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';

    protected static \UnitEnum|string|null $navigationGroup = 'Sozlamalar';

    protected static ?string $navigationLabel = 'Avto Vodiy profil';

    protected static ?string $title = 'Avto Vodiy profilini tahrirlash';

    protected static ?string $slug = 'avto-vodiy-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $avtoVodiy = $this->getAvtoVodiyUser();
        if (! $avtoVodiy) {
            Notification::make()
                ->title('Avto Vodiy foydalanuvchi topilmadi')
                ->danger()
                ->send();

            return;
        }

        $this->form->fill([
            'name' => $avtoVodiy->name,
            'phone' => $avtoVodiy->phone,
            'avatar_path' => $avtoVodiy->avatar_path ?: null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Ism')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefon raqam')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                FileUpload::make('avatar_path')
                    ->label('Profil rasmi')
                    ->image()
                    ->directory(fn () => 'avatars/' . ($this->getAvtoVodiyUser()?->id ?? 'temp'))
                    ->disk(config('moshina_elon.images.disk', 'r2'))
                    ->maxSize(10240)
                    ->avatar()
                    ->maxFiles(1)
                    ->fetchFileInformation(false)
                    ->getUploadedFileUsing(function (BaseFileUpload $component, string $file): ?array {
                        $avtoVodiy = $this->getAvtoVodiyUser();
                        $url = ($avtoVodiy && $avtoVodiy->avatar_path === $file)
                            ? $avtoVodiy->avatar_url
                            : Storage::disk($component->getDiskName())->url($file);
                        return [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => 'image/jpeg',
                            'url' => $url,
                        ];
                    })
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $avtoVodiy = $this->getAvtoVodiyUser();
        if (! $avtoVodiy) {
            return;
        }

        $data = $this->form->getState();

        $avtoVodiy->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
        ]);

        if (! empty($data['avatar_path'])) {
            $newPath = is_array($data['avatar_path']) ? ($data['avatar_path'][0] ?? null) : $data['avatar_path'];
            $newPath = is_string($newPath) ? $newPath : null;
            if ($newPath) {
                $disk = config('moshina_elon.images.disk', 'r2');
                if (! empty($avtoVodiy->avatar_path) && $avtoVodiy->avatar_path !== $newPath) {
                    try {
                        Storage::disk($avtoVodiy->avatar_disk ?: $disk)->delete($avtoVodiy->avatar_path);
                    } catch (Throwable) {
                        // Ignore
                    }
                }
                $avtoVodiy->update([
                    'avatar_path' => $newPath,
                    'avatar_disk' => $disk,
                ]);
            }
        }

        Notification::make()
            ->title('Profil saqlandi')
            ->success()
            ->send();
    }

    protected function getAvtoVodiyUser(): ?User
    {
        return User::where('phone', config('chat.avto_vodiy_phone'))->first();
    }

    public function getView(): string
    {
        return 'filament.pages.avto-vodiy-profile';
    }
}

<?php

namespace App\Filament\Pages;

use App\Models\MoshinaElon;
use App\Services\MoshinaElonService;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ElonListPage extends Page
{
    use WithPagination;

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static \UnitEnum|string|null $navigationGroup = 'E\'lonlar';

    protected static ?string $navigationLabel = 'E\'lonlar';

    protected static ?string $title = 'Moshina e\'lonlari';

    protected static ?int $navigationSort = 1;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'holati')]
    public ?string $statusFilter = null;

    public function getView(): string
    {
        return 'filament.pages.elon-list';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Moshina e\'lonlari';
    }

    public function getElonlarProperty()
    {
        $query = MoshinaElon::query()
            ->with(['user:id,name,phone', 'category:id,name', 'images'])
            ->orderByDesc('created_at');

        if (trim($this->search) !== '') {
            $q = trim($this->search);
            $query->where(function ($qry) use ($q) {
                $qry->where('marka', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%")
                    ->orWhere('narx', 'like', "%{$q}%")
                    ->orWhere('shahar', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%"));
            });
        }

        if ($this->statusFilter && in_array($this->statusFilter, ['active', 'sold', 'inactive'])) {
            $query->where('holati', $this->statusFilter);
        }

        return $query->paginate(12);
    }

    public function deleteElon(int $id): void
    {
        $elon = MoshinaElon::find($id);
        if (!$elon) {
            return;
        }
        $this->authorize('delete', $elon);
        app(MoshinaElonService::class)->delete($elon);
        Notification::make()
            ->title('E\'lon o\'chirildi')
            ->success()
            ->send();
    }
}

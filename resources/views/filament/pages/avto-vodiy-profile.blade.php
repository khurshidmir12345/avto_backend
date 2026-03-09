<x-filament-panels::page>
    @php
        $avtoVodiy = \App\Models\User::where('phone', config('chat.avto_vodiy_phone'))->first();
    @endphp

    @if(!$avtoVodiy)
        <x-filament::callout color="danger" icon="heroicon-o-exclamation-triangle">
            Avto Vodiy foydalanuvchi topilmadi. <code>php artisan db:seed --class=AdminSeeder</code> ni ishga tushiring.
        </x-filament::callout>
    @else
        <x-filament::callout color="warning" icon="heroicon-o-information-circle" class="mb-4">
            Telefon raqamni o'zgartirsangiz, <code>.env</code> faylida <code>AVTO_VODIY_PHONE</code> ni ham yangilang va <code>php artisan config:clear</code> ishga tushiring.
        </x-filament::callout>

        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament::button type="submit">
                    Saqlash
                </x-filament::button>
            </div>
        </form>
    @endif
</x-filament-panels::page>

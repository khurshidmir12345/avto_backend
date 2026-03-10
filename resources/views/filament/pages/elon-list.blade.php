<x-filament-panels::page>
    @push('styles')
    <style>
        .elon-list-container { padding: 0; }
        .elon-list-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-bottom: 20px;
            padding: 16px;
            background: rgb(249 250 251);
            border-radius: 12px;
            border: 1px solid rgb(229 231 235);
        }
        .dark .elon-list-toolbar {
            background: rgb(31 41 55);
            border-color: rgb(55 65 81);
        }
        .elon-search-input {
            flex: 1;
            min-width: 200px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid rgb(209 213 219);
            font-size: 0.875rem;
            background: #fff;
            color: rgb(17 24 39);
        }
        .elon-search-input::placeholder { color: rgb(156 163 175); }
        .dark .elon-search-input {
            background: rgb(55 65 81);
            border-color: rgb(75 85 99);
            color: rgb(243 244 246);
        }
        .elon-filter-select {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid rgb(209 213 219);
            font-size: 0.875rem;
            background: #fff;
            color: rgb(17 24 39);
        }
        .dark .elon-filter-select {
            background: rgb(55 65 81);
            border-color: rgb(75 85 99);
            color: rgb(243 244 246);
        }
        .elon-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .elon-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgb(229 231 235);
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .elon-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .dark .elon-card {
            background: rgb(31 41 55);
            border-color: rgb(55 65 81);
        }
        .dark .elon-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        /* 7 tagacha rasm — kichik thumbnail grid (4 ustun: 4+3 qator) */
        .elon-card-images {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            padding: 8px;
            background: rgb(243 244 246);
            min-height: 110px;
        }
        .dark .elon-card-images { background: rgb(55 65 81); }
        .elon-card-thumb {
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 6px;
            background: rgb(229 231 235);
        }
        .dark .elon-card-thumb { background: rgb(75 85 99); }
        .elon-card-images-empty {
            grid-column: 1 / -1;
            aspect-ratio: 16/10;
            background: rgb(229 231 235);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgb(156 163 175);
        }
        .dark .elon-card-images-empty {
            background: rgb(55 65 81);
            color: rgb(107 114 128);
        }
        .elon-card-body {
            padding: 14px;
        }
        .elon-card-title {
            font-weight: 600;
            font-size: 1rem;
            color: rgb(17 24 39);
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .dark .elon-card-title { color: rgb(243 244 246); }
        .elon-card-meta {
            font-size: 0.8125rem;
            color: rgb(107 114 128);
            margin-bottom: 6px;
        }
        .dark .elon-card-meta { color: rgb(156 163 175); }
        .elon-card-price {
            font-weight: 700;
            font-size: 1.125rem;
            color: rgb(245 158 11);
            margin-bottom: 8px;
        }
        .elon-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid rgb(243 244 246);
        }
        .dark .elon-card-footer { border-color: rgb(55 65 81); }
        .elon-card-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }
        .elon-card-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .elon-card-btn:hover { opacity: 0.9; }
        .elon-card-btn-edit {
            background: rgb(245 158 11);
            color: #fff;
        }
        .elon-card-btn-delete {
            background: rgb(239 68 68);
            color: #fff;
        }
        .elon-card-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 500;
        }
        .elon-card-badge.active { background: rgb(187 247 208); color: rgb(22 101 52); }
        .elon-card-badge.sold { background: rgb(229 231 235); color: rgb(75 85 99); }
        .elon-card-badge.inactive { background: rgb(254 215 170); color: rgb(154 52 18); }
        .dark .elon-card-badge.active { background: rgb(22 101 52); color: rgb(187 247 208); }
        .dark .elon-card-badge.sold { background: rgb(75 85 99); color: rgb(209 213 219); }
        .dark .elon-card-badge.inactive { background: rgb(154 52 18); color: rgb(254 215 170); }
        .elon-card-link {
            font-size: 0.8125rem;
            color: rgb(245 158 11);
            font-weight: 500;
            text-decoration: none;
        }
        .elon-card-link:hover { text-decoration: underline; }
        .elon-empty {
            text-align: center;
            padding: 48px 24px;
            color: rgb(107 114 128);
        }
        .dark .elon-empty { color: rgb(156 163 175); }
        .elon-pagination {
            margin-top: 24px;
            overflow-x: auto;
            max-width: 100%;
        }
        .elon-pagination .fi-pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
    @endpush

    <div class="elon-list-container">
        {{-- Qidiruv va filter --}}
        <div class="elon-list-toolbar">
            <div style="flex: 1; min-width: 200px; display: flex; gap: 8px;">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Marka, model, narx, shahar bo'yicha qidirish..."
                    class="elon-search-input"
                />
                <select wire:model.live="statusFilter" class="elon-filter-select">
                    <option value="">Barcha holatlar</option>
                    <option value="active">Faol</option>
                    <option value="sold">Sotilgan</option>
                    <option value="inactive">Nofaol</option>
                </select>
            </div>
        </div>

        {{-- Kartalar --}}
        @if($this->elonlar->isEmpty())
            <div class="elon-empty">
                <svg width="64" height="64" style="margin: 0 auto 16px; opacity: 0.4; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p style="font-size: 1rem; font-weight: 500;">E'lonlar topilmadi</p>
                <p style="font-size: 0.875rem; margin-top: 4px;">Qidiruv yoki filterni o'zgartiring</p>
            </div>
        @else
            <div class="elon-card-grid">
                @foreach($this->elonlar as $elon)
                    @php
                        $images = $elon->images->take(7);
                        $holatLabel = match($elon->holati) {
                            'active' => 'Faol',
                            'sold' => 'Sotilgan',
                            'inactive' => 'Nofaol',
                            default => $elon->holati,
                        };
                    @endphp
                    <div class="elon-card">
                        <div class="elon-card-images">
                            @if($images->isEmpty())
                                <div class="elon-card-images-empty">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span style="font-size: 0.75rem; margin-left: 6px;">Rasm yo'q</span>
                                </div>
                            @else
                                @foreach($images as $img)
                                    <img src="{{ $img->thumb_url }}" alt="" class="elon-card-thumb" loading="lazy" title="Rasm {{ $loop->iteration }}/{{ $images->count() }}" />
                                @endforeach
                            @endif
                        </div>
                        <div class="elon-card-body">
                            <div class="elon-card-title">{{ $elon->marka }} {{ $elon->model }}</div>
                            <div class="elon-card-meta">{{ $elon->yil }} yil · {{ $elon->shahar ?? '-' }} · {{ $images->count() }}/7 rasm</div>
                            <div class="elon-card-price">{{ number_format((float) $elon->narx) }} {{ $elon->valyuta ?? 'UZS' }}</div>
                            <div class="elon-card-footer">
                                <span class="elon-card-badge {{ $elon->holati }}">{{ $holatLabel }}</span>
                            </div>
                            <div class="elon-card-actions">
                                <a href="{{ \App\Filament\Resources\MoshinaElons\MoshinaElonResource::getUrl('edit', ['record' => $elon]) }}" class="elon-card-btn elon-card-btn-edit">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Tahrirlash
                                </a>
                                <button type="button" class="elon-card-btn elon-card-btn-delete" wire:click="deleteElon({{ $elon->id }})" wire:confirm="E'loni o'chirishni xohlaysizmi?">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    O'chirish
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginatsiya — Filament uslubida qisqa --}}
            @if($this->elonlar->hasPages())
                <div class="elon-pagination">
                    <x-filament::pagination
                        :paginator="$this->elonlar"
                        :page-options="[]"
                    />
                </div>
            @endif
        @endif
    </div>
</x-filament-panels::page>

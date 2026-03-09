<x-filament-panels::page>
    @push('styles')
    <style>
        .avto-chat-container { display: flex; gap: 0; height: calc(100vh - 12rem); min-height: 600px; border-radius: 0.75rem; overflow: hidden; border: 1px solid rgb(229 231 235); background: white; }
        .dark .avto-chat-container { border-color: rgb(55 65 81); background: rgb(17 24 39); }
        .avto-chat-sidebar { width: 320px; min-width: 320px; display: flex; flex-direction: column; border-right: 1px solid rgb(229 231 235); background: rgb(249 250 251 / 0.5); }
        .dark .avto-chat-sidebar { border-color: rgb(55 65 81); background: rgb(31 41 55 / 0.5); }
        .avto-chat-main { flex: 1; min-width: 0; display: flex; flex-direction: column; background: white; }
        .dark .avto-chat-main { background: rgb(17 24 39); }
        .avto-chat-user-btn { width: 100%; display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; margin-bottom: 4px; text-align: left; border: none; cursor: pointer; transition: all 0.2s; }
        .avto-chat-user-btn:hover { background: rgb(229 231 235 / 0.8); }
        .dark .avto-chat-user-btn:hover { background: rgb(55 65 81 / 0.8); }
        .avto-chat-user-btn.selected { background: rgb(245 158 11); color: white !important; }
        .avto-chat-user-btn.selected * { color: inherit !important; }
        .dark .avto-chat-user-btn.selected { background: rgb(217 119 6); }
        .avto-avatar-wrap { width: 48px; height: 48px; min-width: 48px; min-height: 48px; border-radius: 9999px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .avto-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; max-width: 48px; max-height: 48px; }
        .avto-avatar-sm { width: 40px; height: 40px; min-width: 40px; min-height: 40px; }
        .avto-avatar-sm img { max-width: 40px; max-height: 40px; }
    </style>
    @endpush
    @php
        $avtoVodiy = $this->getAvtoVodiyUser();
        $chatList = $this->chatList;
    @endphp

    @if(!$avtoVodiy)
        <x-filament::callout color="danger" icon="heroicon-o-exclamation-triangle">
            Avto Vodiy foydalanuvchi topilmadi. <code>php artisan db:seed --class=AdminSeeder</code> ni ishga tushiring.
        </x-filament::callout>
    @else
        <div class="avto-chat-container">
            {{-- CHAP SIDEBAR — Telegram uslubida foydalanuvchilar ro'yxati --}}
            <div class="avto-chat-sidebar">
                <div style="padding: 16px; border-bottom: 1px solid rgb(229 231 235); flex-shrink: 0;">
                    <h2 style="font-size: 1.125rem; font-weight: 600;">Chatlar</h2>
                    <p style="font-size: 0.75rem; color: rgb(107 114 128); margin-top: 2px;">Foydalanuvchini tanlang</p>
                    <div style="margin-top: 12px; position: relative;">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="searchQuery"
                            placeholder="Ism yoki telefon bo'yicha qidirish..."
                            style="width: 100%; padding: 10px 12px 10px 40px; border-radius: 10px; border: 1px solid rgb(209 213 219); font-size: 0.875rem; outline: none;"
                        />
                        <svg width="18" height="18" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: rgb(156 163 175); pointer-events: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div style="flex: 1; overflow-y: auto; padding: 8px;">
                    @forelse($chatList as $item)
                        @php
                            $user = $item->user;
                            $avatarUrl = $user->avatar_url ?? null;
                            $isSelected = ($item->conversation && $selectedConversationId === $item->conversation->id)
                                || (!$item->conversation && $selectedUserId === $user->id);
                        @endphp
                        <button
                            wire:click="{{ $item->conversation ? "selectConversation({$item->conversation->id})" : "selectUser({$user->id})" }}"
                            class="avto-chat-user-btn {{ $isSelected ? 'selected' : '' }}"
                        >
                            {{-- Avatar — inline style bilan rasm cheklangan --}}
                            <div class="avto-avatar-wrap">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="" />
                                @else
                                    <span style="font-size: 1.125rem; font-weight: 600;">{{ substr($user->name ?? '?', 0, 1) }}</span>
                                @endif
                            </div>
                            <div style="min-width: 0; flex: 1; overflow: hidden;">
                                <div style="font-weight: 600; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $user->name ?? 'Noma\'lum' }}
                                </div>
                                <div style="font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $user->phone }}
                                </div>
                                @if($item->last_message_preview)
                                    <div style="font-size: 0.75rem; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $item->last_message_from_me ? 'Siz: ' : '' }}{{ $item->last_message_preview }}
                                    </div>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div style="padding: 32px 16px; text-align: center; color: rgb(107 114 128); font-size: 0.875rem;">
                            <svg width="48" height="48" style="margin: 0 auto 12px; opacity: 0.5; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <p>{{ $this->searchQuery ? 'Qidiruv bo\'yicha foydalanuvchi topilmadi' : 'Foydalanuvchilar yo\'q' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- O'NG QISIM — Chat (ekranni yarmi) --}}
            <div class="avto-chat-main">
                @if($selectedConversationId)
                    @php
                        $conv = \App\Models\Conversation::find($selectedConversationId);
                        $other = $conv ? $this->getOtherUser($conv) : null;
                        $messages = $this->messages;
                        $otherAvatarUrl = $other?->avatar_url ?? null;
                    @endphp
                    {{-- Chat header — tanlangan foydalanuvchi --}}
                    <div style="flex-shrink: 0; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid rgb(229 231 235);">
                        <div class="avto-avatar-wrap avto-avatar-sm" style="background: rgb(229 231 235);">
                            @if($otherAvatarUrl)
                                <img src="{{ $otherAvatarUrl }}" alt="" />
                            @else
                                <span style="font-size: 0.875rem; font-weight: 600;">{{ substr($other?->name ?? '?', 0, 1) }}</span>
                            @endif
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 600;">{{ $other?->name ?? 'Noma\'lum' }}</div>
                            <div style="font-size: 0.75rem; color: rgb(107 114 128);">{{ $other?->phone }}</div>
                        </div>
                    </div>

                    {{-- Xabarlar — Telegram uslubida bublar --}}
                    <div style="flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 8px; background: rgb(249 250 251 / 0.3);">
                        @foreach($messages as $msg)
                            @php
                                $isFromMe = $msg->sender_id === $avtoVodiy->id;
                            @endphp
                            <div style="display: flex; {{ $isFromMe ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                                <div style="max-width: 70%; border-radius: 16px; padding: 10px 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); {{ $isFromMe ? 'background: rgb(245 158 11); color: white; border-bottom-right-radius: 4px;' : 'background: white; border: 1px solid rgb(243 244 246); border-bottom-left-radius: 4px;' }}">
                                    @if(!$isFromMe)
                                        <div style="font-size: 0.75rem; font-weight: 500; color: rgb(217 119 6); margin-bottom: 2px;">{{ $msg->sender->name ?? 'Noma\'lum' }}</div>
                                    @endif
                                    @if($msg->type === 'image' && $msg->admin_media_url)
                                        <a href="{{ $msg->admin_media_url }}" target="_blank" rel="noopener" style="display: block; margin-bottom: 6px;">
                                            <img src="{{ $msg->admin_media_url }}" alt="Rasm" style="max-width: 100%; max-height: 280px; border-radius: 8px; display: block;" />
                                        </a>
                                    @elseif($msg->type === 'voice' && $msg->admin_media_url)
                                        <div style="margin-bottom: 6px;">
                                            <audio controls src="{{ $msg->admin_media_url }}" style="max-width: 100%; height: 36px; max-height: 48px;" preload="metadata"></audio>
                                        </div>
                                    @endif
                                    @if($msg->body)
                                        <div style="font-size: 0.875rem; white-space: pre-wrap; word-break: break-word;">{{ $msg->body }}</div>
                                    @endif
                                    <div style="font-size: 10px; {{ $isFromMe ? 'color: rgba(255,255,255,0.8);' : 'color: rgb(156 163 175);' }} margin-top: 4px; text-align: right;">
                                        {{ $msg->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Xabar yozish input — Enter = yuborish, Shift+Enter = yangi qator --}}
                    <div style="flex-shrink: 0; padding: 16px; border-top: 1px solid rgb(229 231 235);">
                        <form wire:submit="sendMessage" style="display: flex; gap: 8px; align-items: flex-end;">
                            <textarea
                                wire:model="messageBody"
                                placeholder="Xabar yozing... (Enter — yuborish, Shift+Enter — yangi qator)"
                                style="flex: 1; border-radius: 12px; border: 1px solid rgb(209 213 219); padding: 12px 16px; font-size: 0.875rem; resize: none;"
                                rows="2"
                                x-data
                                x-on:keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); $wire.sendMessage(); }"
                            ></textarea>
                            <button type="submit" style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; background: rgb(245 158 11); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgb(217 119 6)'" onmouseout="this.style.background='rgb(245 158 11)'" title="Yuborish (Enter)">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" style="display: block;">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                </svg>
                            </button>
                        </form>
                        <p style="font-size: 0.75rem; color: rgb(107 114 128); margin-top: 6px;">Xabar {{ $other?->name ?? 'foydalanuvchi' }}ga yuboriladi</p>
                    </div>
                @else
                    {{-- Chat tanlanmagan — placeholder --}}
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: rgb(156 163 175); padding: 32px;">
                        <div style="width: 96px; height: 96px; border-radius: 9999px; background: rgb(243 244 246); display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p style="font-weight: 500; font-size: 1rem;">Chapdan foydalanuvchini tanlang</p>
                        <p style="font-size: 0.875rem; margin-top: 4px;">Suhbat ochiladi, yozgan xabaringiz tanlangan userga yetib boradi</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</x-filament-panels::page>

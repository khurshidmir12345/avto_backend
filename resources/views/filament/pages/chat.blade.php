<x-filament-panels::page>
    @push('styles')
    <style>
        /* ===== CHAT CONTAINER ===== */
        .avto-chat-container {
            display: flex;
            gap: 0;
            height: calc(100vh - 12rem);
            min-height: 600px;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid rgb(229 231 235);
            background: #ffffff;
        }
        .dark .avto-chat-container {
            border-color: rgb(55 65 81);
            background: rgb(17 24 39);
        }

        /* ===== SIDEBAR ===== */
        .avto-chat-sidebar {
            width: 320px;
            min-width: 320px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgb(229 231 235);
            background: rgb(249 250 251);
        }
        .dark .avto-chat-sidebar {
            border-color: rgb(55 65 81);
            background: rgb(31 41 55);
        }

        .avto-chat-sidebar-header {
            padding: 16px;
            border-bottom: 1px solid rgb(229 231 235);
            flex-shrink: 0;
        }
        .dark .avto-chat-sidebar-header {
            border-color: rgb(55 65 81);
        }

        .avto-chat-sidebar-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: rgb(17 24 39);
        }
        .dark .avto-chat-sidebar-title {
            color: rgb(243 244 246);
        }

        .avto-chat-sidebar-subtitle {
            font-size: 0.75rem;
            color: rgb(107 114 128);
            margin-top: 2px;
        }
        .dark .avto-chat-sidebar-subtitle {
            color: rgb(156 163 175);
        }

        .avto-chat-search-wrap {
            margin-top: 12px;
            position: relative;
        }

        .avto-chat-search-input {
            width: 100%;
            padding: 10px 12px 10px 40px;
            border-radius: 10px;
            border: 1px solid rgb(209 213 219);
            font-size: 0.875rem;
            outline: none;
            background: #ffffff;
            color: rgb(17 24 39);
        }
        .avto-chat-search-input::placeholder {
            color: rgb(156 163 175);
        }
        .dark .avto-chat-search-input {
            background: rgb(55 65 81);
            border-color: rgb(75 85 99);
            color: rgb(243 244 246);
        }
        .dark .avto-chat-search-input::placeholder {
            color: rgb(156 163 175);
        }

        .avto-chat-search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgb(156 163 175);
            pointer-events: none;
        }

        /* ===== USER LIST BUTTON ===== */
        .avto-chat-user-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 4px;
            text-align: left;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            background: transparent;
            color: rgb(17 24 39);
        }
        .avto-chat-user-btn:hover {
            background: rgb(229 231 235);
        }
        .dark .avto-chat-user-btn {
            color: rgb(243 244 246);
        }
        .dark .avto-chat-user-btn:hover {
            background: rgb(55 65 81);
        }
        .avto-chat-user-btn.selected {
            background: rgb(245 158 11);
            color: #ffffff !important;
        }
        .avto-chat-user-btn.selected * {
            color: #ffffff !important;
        }
        .dark .avto-chat-user-btn.selected {
            background: rgb(217 119 6);
        }

        .avto-chat-user-btn .avto-user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: inherit;
        }
        .avto-chat-user-btn .avto-user-phone {
            font-size: 0.75rem;
            opacity: 0.85;
            color: inherit;
        }
        .avto-chat-user-btn .avto-user-preview {
            font-size: 0.75rem;
            margin-top: 2px;
            opacity: 0.75;
            color: inherit;
        }

        /* ===== AVATAR ===== */
        .avto-avatar-wrap {
            width: 48px;
            height: 48px;
            min-width: 48px;
            min-height: 48px;
            border-radius: 9999px;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgb(229 231 235);
            color: rgb(107 114 128);
        }
        .dark .avto-avatar-wrap {
            background: rgb(75 85 99);
            color: rgb(209 213 219);
        }
        .avto-avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avto-avatar-sm {
            width: 40px;
            height: 40px;
            min-width: 40px;
            min-height: 40px;
        }

        /* ===== MAIN CHAT AREA ===== */
        .avto-chat-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: rgb(243 244 246);
        }
        .dark .avto-chat-main {
            background: rgb(17 24 39);
        }

        /* Chat header */
        .avto-chat-header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid rgb(229 231 235);
            background: #ffffff;
        }
        .dark .avto-chat-header {
            border-color: rgb(55 65 81);
            background: rgb(31 41 55);
        }
        .avto-chat-header-name {
            font-weight: 600;
            font-size: 1rem;
            color: rgb(17 24 39);
        }
        .dark .avto-chat-header-name {
            color: rgb(243 244 246);
        }
        .avto-chat-header-phone {
            font-size: 0.75rem;
            color: rgb(107 114 128);
        }
        .dark .avto-chat-header-phone {
            color: rgb(156 163 175);
        }

        /* Messages area */
        .avto-chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: rgb(243 244 246);
        }
        .dark .avto-chat-messages {
            background: rgb(17 24 39);
        }

        /* Message bubble — mijozdan */
        .avto-msg-bubble {
            max-width: 70%;
            border-radius: 16px;
            padding: 12px 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        }
        .avto-msg-bubble.from-me {
            background: rgb(245 158 11);
            color: #ffffff;
            border-bottom-right-radius: 4px;
            margin-left: auto;
        }
        .avto-msg-bubble.from-other {
            background: #ffffff;
            border: 1px solid rgb(229 231 235);
            border-bottom-left-radius: 4px;
            color: rgb(17 24 39);
        }
        .dark .avto-msg-bubble.from-other {
            background: rgb(55 65 81);
            border-color: rgb(75 85 99);
            color: rgb(243 244 246);
        }
        .avto-msg-sender-name {
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .avto-msg-bubble.from-other .avto-msg-sender-name {
            color: rgb(217 119 6);
        }
        .dark .avto-msg-bubble.from-other .avto-msg-sender-name {
            color: rgb(251 191 36);
        }
        .avto-msg-body {
            font-size: 0.9375rem;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .avto-msg-time {
            font-size: 0.6875rem;
            margin-top: 6px;
            text-align: right;
        }
        .avto-msg-bubble.from-me .avto-msg-time {
            color: rgba(255,255,255,0.85);
        }
        .avto-msg-bubble.from-other .avto-msg-time {
            color: rgb(107 114 128);
        }
        .dark .avto-msg-bubble.from-other .avto-msg-time {
            color: rgb(156 163 175);
        }

        /* Input area */
        .avto-chat-input-wrap {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid rgb(229 231 235);
            background: #ffffff;
        }
        .dark .avto-chat-input-wrap {
            border-color: rgb(55 65 81);
            background: rgb(31 41 55);
        }
        .avto-chat-textarea {
            flex: 1;
            border-radius: 12px;
            border: 1px solid rgb(209 213 219);
            padding: 12px 16px;
            font-size: 0.9375rem;
            resize: none;
            background: #ffffff;
            color: rgb(17 24 39);
        }
        .avto-chat-textarea::placeholder {
            color: rgb(156 163 175);
        }
        .dark .avto-chat-textarea {
            background: rgb(55 65 81);
            border-color: rgb(75 85 99);
            color: rgb(243 244 246);
        }
        .dark .avto-chat-textarea::placeholder {
            color: rgb(156 163 175);
        }
        .avto-chat-send-btn {
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgb(245 158 11);
            color: #ffffff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .avto-chat-send-btn:hover {
            background: rgb(217 119 6);
        }
        .avto-chat-input-hint {
            font-size: 0.75rem;
            color: rgb(107 114 128);
            margin-top: 8px;
        }
        .dark .avto-chat-input-hint {
            color: rgb(156 163 175);
        }

        /* Empty state */
        .avto-chat-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }
        .avto-chat-empty-icon {
            width: 96px;
            height: 96px;
            border-radius: 9999px;
            background: rgb(229 231 235);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: rgb(156 163 175);
        }
        .dark .avto-chat-empty-icon {
            background: rgb(55 65 81);
            color: rgb(107 114 128);
        }
        .avto-chat-empty-title {
            font-weight: 600;
            font-size: 1rem;
            color: rgb(75 85 99);
        }
        .dark .avto-chat-empty-title {
            color: rgb(209 213 219);
        }
        .avto-chat-empty-desc {
            font-size: 0.875rem;
            margin-top: 4px;
            color: rgb(107 114 128);
        }
        .dark .avto-chat-empty-desc {
            color: rgb(156 163 175);
        }

        /* Empty list in sidebar */
        .avto-chat-empty-list {
            padding: 32px 16px;
            text-align: center;
            font-size: 0.875rem;
            color: rgb(107 114 128);
        }
        .dark .avto-chat-empty-list {
            color: rgb(156 163 175);
        }
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
            {{-- CHAP SIDEBAR — Foydalanuvchilar ro'yxati --}}
            <div class="avto-chat-sidebar">
                <div class="avto-chat-sidebar-header">
                    <h2 class="avto-chat-sidebar-title">Chatlar</h2>
                    <p class="avto-chat-sidebar-subtitle">Foydalanuvchini tanlang</p>
                    <div class="avto-chat-search-wrap">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="searchQuery"
                            placeholder="Ism yoki telefon bo'yicha qidirish..."
                            class="avto-chat-search-input"
                        />
                        <svg class="avto-chat-search-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <div class="avto-avatar-wrap">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="" />
                                @else
                                    <span style="font-size: 1.125rem; font-weight: 600;">{{ substr($user->name ?? '?', 0, 1) }}</span>
                                @endif
                            </div>
                            <div style="min-width: 0; flex: 1; overflow: hidden;">
                                <div class="avto-user-name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $user->name ?? 'Noma\'lum' }}
                                </div>
                                <div class="avto-user-phone" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $user->phone }}
                                </div>
                                @if($item->last_message_preview)
                                    <div class="avto-user-preview" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $item->last_message_from_me ? 'Siz: ' : '' }}{{ $item->last_message_preview }}
                                    </div>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="avto-chat-empty-list">
                            <svg width="48" height="48" style="margin: 0 auto 12px; opacity: 0.5; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <p>{{ $this->searchQuery ? 'Qidiruv bo\'yicha foydalanuvchi topilmadi' : 'Foydalanuvchilar yo\'q' }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- O'NG QISIM — Chat --}}
            <div class="avto-chat-main">
                @if($selectedConversationId)
                    @php
                        $conv = \App\Models\Conversation::find($selectedConversationId);
                        $other = $conv ? $this->getOtherUser($conv) : null;
                        $messages = $this->messages;
                        $otherAvatarUrl = $other?->avatar_url ?? null;
                    @endphp
                    {{-- Chat header --}}
                    <div class="avto-chat-header">
                        <div class="avto-avatar-wrap avto-avatar-sm">
                            @if($otherAvatarUrl)
                                <img src="{{ $otherAvatarUrl }}" alt="" />
                            @else
                                <span style="font-size: 0.875rem; font-weight: 600;">{{ substr($other?->name ?? '?', 0, 1) }}</span>
                            @endif
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <div class="avto-chat-header-name">{{ $other?->name ?? 'Noma\'lum' }}</div>
                            <div class="avto-chat-header-phone">{{ $other?->phone }}</div>
                        </div>
                    </div>

                    {{-- Xabarlar --}}
                    <div class="avto-chat-messages">
                        @foreach($messages as $msg)
                            @php
                                $isFromMe = $msg->sender_id === $avtoVodiy->id;
                            @endphp
                            <div style="display: flex; {{ $isFromMe ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                                <div class="avto-msg-bubble {{ $isFromMe ? 'from-me' : 'from-other' }}">
                                    @if(!$isFromMe)
                                        <div class="avto-msg-sender-name">{{ $msg->sender->name ?? 'Noma\'lum' }}</div>
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
                                        <div class="avto-msg-body">{{ $msg->body }}</div>
                                    @endif
                                    <div class="avto-msg-time">{{ $msg->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Xabar yozish --}}
                    <div class="avto-chat-input-wrap">
                        <form wire:submit="sendMessage" style="display: flex; gap: 10px; align-items: flex-end;">
                            <textarea
                                wire:model="messageBody"
                                placeholder="Xabar yozing... (Enter — yuborish, Shift+Enter — yangi qator)"
                                class="avto-chat-textarea"
                                rows="2"
                                x-data
                                x-on:keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); $wire.sendMessage(); }"
                            ></textarea>
                            <button type="submit" class="avto-chat-send-btn" title="Yuborish (Enter)">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                </svg>
                            </button>
                        </form>
                        <p class="avto-chat-input-hint">Xabar {{ $other?->name ?? 'foydalanuvchi' }}ga yuboriladi</p>
                    </div>
                @else
                    {{-- Chat tanlanmagan --}}
                    <div class="avto-chat-empty">
                        <div class="avto-chat-empty-icon">
                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="avto-chat-empty-title">Chapdan foydalanuvchini tanlang</p>
                        <p class="avto-chat-empty-desc">Suhbat ochiladi, yozgan xabaringiz tanlangan userga yetib boradi</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</x-filament-panels::page>

<?php

namespace App\Filament\Pages;

use App\Models\Conversation;
use App\Models\Message;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Chat extends Page
{

    protected static \BackedEnum|string|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static \UnitEnum|string|null $navigationGroup = 'Boshqaruv';

    protected static ?string $navigationLabel = 'Chat';

    protected static ?string $title = 'Foydalanuvchilar bilan chat';

    public ?int $selectedConversationId = null;

    public ?int $selectedUserId = null;

    public function getView(): string
    {
        return 'filament.pages.chat';
    }

    public string $messageBody = '';

    public string $searchQuery = '';

    public function mount(): void
    {
        //
    }

    public function getAvtoVodiyUser(): ?User
    {
        return User::where('phone', config('chat.avto_vodiy_phone'))->first();
    }

    /**
     * Barcha foydalanuvchilar (admin va Avto Vodiy dan tashqari) — chat ro'yxatida avtomatik chiqadi.
     */
    public function getChatListProperty()
    {
        $avtoVodiy = $this->getAvtoVodiyUser();
        if (!$avtoVodiy) {
            return collect();
        }

        $conversations = Conversation::query()
            ->where(function ($q) use ($avtoVodiy) {
                $q->where('user_a_id', $avtoVodiy->id)
                    ->orWhere('user_b_id', $avtoVodiy->id);
            })
            ->with(['userA:id,name,phone,avatar_path,avatar_disk', 'userB:id,name,phone,avatar_path,avatar_disk'])
            ->orderByDesc('last_message_at')
            ->get();

        $userIdsWithChat = $conversations->flatMap(fn ($c) => [$c->user_a_id, $c->user_b_id])
            ->unique()
            ->filter(fn ($id) => $id !== $avtoVodiy->id)
            ->values();

        $usersWithoutChat = User::query()
            ->where('id', '!=', $avtoVodiy->id)
            ->where('is_admin', false)
            ->whereNotIn('id', $userIdsWithChat)
            ->select('id', 'name', 'phone', 'avatar_path', 'avatar_disk')
            ->orderBy('name')
            ->get();

        $convIds = $conversations->pluck('id')->toArray();
        $lastMsgByConv = collect();
        if (!empty($convIds)) {
            foreach (Message::whereIn('conversation_id', $convIds)->orderByDesc('created_at')->get() as $m) {
                if (!$lastMsgByConv->has($m->conversation_id)) {
                    $lastMsgByConv->put($m->conversation_id, $m);
                }
            }
        }

        $unreadCounts = collect();
        if (!empty($convIds)) {
            $unreadCounts = Message::whereIn('conversation_id', $convIds)
                ->where('sender_id', '!=', $avtoVodiy->id)
                ->where('read_at', false)
                ->selectRaw('conversation_id, count(*) as cnt')
                ->groupBy('conversation_id')
                ->pluck('cnt', 'conversation_id');
        }

        $result = collect();
        foreach ($conversations as $conv) {
            $other = $this->getOtherUser($conv);
            if ($other) {
                $lastMsg = $lastMsgByConv->get($conv->id);
                $result->push((object) [
                    'user' => $other,
                    'conversation' => $conv,
                    'last_message_at' => $conv->last_message_at,
                    'last_message_preview' => $lastMsg ? ($lastMsg->type === Message::TYPE_IMAGE ? '🖼 Rasm' : ($lastMsg->type === Message::TYPE_VOICE ? '🎤 Ovoz' : Str::limit($lastMsg->body ?? '', 35))) : null,
                    'last_message_from_me' => $lastMsg && $lastMsg->sender_id === $avtoVodiy->id,
                    'unread_count' => $unreadCounts->get($conv->id, 0),
                ]);
            }
        }
        foreach ($usersWithoutChat as $user) {
            $result->push((object) [
                'user' => $user,
                'conversation' => null,
                'last_message_at' => null,
                'last_message_preview' => null,
                'last_message_from_me' => false,
                'unread_count' => 0,
            ]);
        }

        $sorted = $result->sortByDesc(fn ($i) => $i->last_message_at?->timestamp ?? 0)->values();

        if (trim($this->searchQuery) !== '') {
            $q = strtolower(trim($this->searchQuery));
            $sorted = $sorted->filter(function ($item) use ($q) {
                $name = strtolower($item->user->name ?? '');
                $phone = $item->user->phone ?? '';
                return str_contains($name, $q) || str_contains($phone, $q);
            })->values();
        }

        return $sorted;
    }

    public function getMessagesProperty()
    {
        if ($this->selectedConversationId) {
            return Message::where('conversation_id', $this->selectedConversationId)
                ->with('sender:id,name')
                ->orderBy('created_at')
                ->get();
        }
        return collect();
    }

    public function getOtherUser(?Conversation $conversation): ?User
    {
        if (!$conversation) {
            return null;
        }
        $avtoVodiy = $this->getAvtoVodiyUser();
        if (!$avtoVodiy) {
            return null;
        }
        return $conversation->user_a_id === $avtoVodiy->id ? $conversation->userB : $conversation->userA;
    }

    public function selectConversation(int $conversationId): void
    {
        $this->selectedConversationId = $conversationId;
        $this->selectedUserId = null;
        $this->messageBody = '';
    }

    public function selectUser(int $userId): void
    {
        $avtoVodiy = $this->getAvtoVodiyUser();
        if (!$avtoVodiy) {
            return;
        }
        $conv = Conversation::findOrCreateBetween($avtoVodiy->id, $userId);
        $this->selectedConversationId = $conv->id;
        $this->selectedUserId = $userId;
        $this->messageBody = '';
    }

    public function sendMessage(): void
    {
        $avtoVodiy = $this->getAvtoVodiyUser();
        if (!$avtoVodiy) {
            Notification::make()
                ->title('Avto Vodiy foydalanuvchi topilmadi')
                ->danger()
                ->send();
            return;
        }

        $body = trim($this->messageBody);
        if (empty($body)) {
            return;
        }

        $conversation = Conversation::find($this->selectedConversationId);
        if (!$conversation) {
            return;
        }

        $isParticipant = $conversation->user_a_id === $avtoVodiy->id || $conversation->user_b_id === $avtoVodiy->id;
        if (!$isParticipant) {
            Notification::make()
                ->title('Ruxsat yo\'q')
                ->danger()
                ->send();
            return;
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $avtoVodiy->id,
            'body' => $body,
            'type' => Message::TYPE_TEXT,
        ]);

        $conversation->update(['last_message_at' => now()]);

        $this->messageBody = '';
        $this->selectedUserId = null;
        $this->dispatch('$refresh');
    }

}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUserId = $request->user()->id;
        $otherUser = $this->getOtherUser($currentUserId);
        $lastMessage = $this->messages->first();

        $unreadCount = $this->messages()
            ->where('sender_id', '!=', $currentUserId)
            ->where('read_at', false)
            ->count();

        return [
            'id' => $this->id,
            'other_user' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'phone' => $otherUser->phone,
                'avatar_url' => $otherUser->avatar_url,
            ],
            'last_message' => $lastMessage ? [
                'id' => $lastMessage->id,
                'body' => $lastMessage->body,
                'type' => $lastMessage->type,
                'sender_id' => $lastMessage->sender_id,
                'created_at' => $lastMessage->created_at?->toIso8601String(),
            ] : null,
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'unread_count' => $unreadCount,
        ];
    }
}

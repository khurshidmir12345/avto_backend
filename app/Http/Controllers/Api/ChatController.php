<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\ChatMediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatMediaService $mediaService
    ) {}

    public function index(Request $request): ResourceCollection
    {
        $user = $request->user();

        $conversations = Conversation::query()
            ->where(function ($q) use ($user) {
                $q->where('user_a_id', $user->id)->orWhere('user_b_id', $user->id);
            })
            ->with(['userA:id,name,phone,avatar_path,avatar_disk', 'userB:id,name,phone,avatar_path,avatar_disk'])
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->orderByDesc('last_message_at')
            ->get();

        return ConversationResource::collection($conversations);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['user_id' => ['required', 'integer', 'exists:users,id']]);

        $otherUserId = (int) $request->user_id;
        $currentUserId = $request->user()->id;

        if ($otherUserId === $currentUserId) {
            return response()->json(['message' => 'O\'zingiz bilan chat qila olmaysiz'], 422);
        }

        $conversation = Conversation::findOrCreateBetween($currentUserId, $otherUserId);
        $conversation->load(['userA:id,name,phone,avatar_path,avatar_disk', 'userB:id,name,phone,avatar_path,avatar_disk']);

        return response()->json([
            'message' => 'Chat topildi',
            'conversation' => new ConversationResource($conversation),
        ], 201);
    }

    public function messages(Request $request, Conversation $conversation): ResourceCollection
    {
        $user = $request->user();
        if ($conversation->user_a_id !== $user->id && $conversation->user_b_id !== $user->id) {
            abort(403, 'Ruxsat yo\'q');
        }

        $perPage = min((int) $request->get('per_page', 30), 50);

        $messages = $conversation->messages()
            ->with('sender:id,name')
            ->latest()
            ->paginate($perPage);

        $messages->getCollection()->each(function (Message $msg) use ($user) {
            if ($msg->sender_id !== $user->id) {
                $msg->update(['read_at' => true]);
            }
        });

        return MessageResource::collection($messages);
    }

    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();
        if ($conversation->user_a_id !== $user->id && $conversation->user_b_id !== $user->id) {
            abort(403, 'Ruxsat yo\'q');
        }

        $body = $request->input('body', '');
        $type = Message::TYPE_TEXT;

        if ($request->hasFile('image')) {
            $rules = ChatMediaService::imageValidationRules();
            $request->validate($rules);
            $media = $this->mediaService->uploadImage($request->file('image'), $conversation->id);
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'body' => $body ?: null,
                'type' => Message::TYPE_IMAGE,
                'media_path' => $media['path'],
                'media_disk' => $media['disk'],
                'media_mime' => $media['mime'],
            ]);
        } elseif ($request->hasFile('voice')) {
            $rules = ChatMediaService::voiceValidationRules();
            $request->validate($rules);
            $media = $this->mediaService->uploadVoice($request->file('voice'), $conversation->id);
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'body' => null,
                'type' => Message::TYPE_VOICE,
                'media_path' => $media['path'],
                'media_disk' => $media['disk'],
                'media_mime' => $media['mime'],
            ]);
        } else {
            $request->validate(['body' => ['required', 'string', 'max:5000']]);
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'body' => $body,
                'type' => Message::TYPE_TEXT,
            ]);
        }

        $conversation->update(['last_message_at' => $message->created_at]);
        $message->load('sender:id,name');

        return response()->json([
            'message' => 'Xabar yuborildi',
            'data' => new MessageResource($message),
        ], 201);
    }

    public function media(Request $request, Message $message): StreamedResponse|JsonResponse
    {
        $user = $request->user();
        $conversation = $message->conversation;

        if ($conversation->user_a_id !== $user->id && $conversation->user_b_id !== $user->id) {
            abort(403, 'Ruxsat yo\'q');
        }

        if (empty($message->media_path)) {
            abort(404, 'Media topilmadi');
        }

        $disk = $message->media_disk ?? config('chat.media.disk', 'r2');

        if (! Storage::disk($disk)->exists($message->media_path)) {
            abort(404, 'Fayl topilmadi');
        }

        $mime = $message->media_mime ?? 'application/octet-stream';
        $filename = basename($message->media_path);

        $stream = Storage::disk($disk)->readStream($message->media_path);
        if ($stream === null) {
            abort(500, 'Fayl o\'qilolmadi');
        }

        return response()->stream(
            function () use ($stream) {
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            },
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control' => 'public, max-age=86400',
            ]
        );
    }

    public function users(Request $request): JsonResponse
    {
        $q = $request->get('q', '');
        $currentUserId = $request->user()->id;

        $avtoVodiy = User::where('phone', config('chat.avto_vodiy_phone'))
            ->select('id', 'name', 'phone', 'avatar_path', 'avatar_disk')
            ->first();

        $users = User::query()
            ->where('id', '!=', $currentUserId)
            ->when($q !== '', fn ($query) => $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            }))
            ->select('id', 'name', 'phone', 'avatar_path', 'avatar_disk')
            ->limit(30)
            ->get();

        $data = $users->map(fn ($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'phone' => $u->phone,
            'avatar_url' => $u->avatar_url,
        ])->values()->all();

        if ($avtoVodiy && $avtoVodiy->id !== $currentUserId) {
            $avtoVodiyItem = [
                'id' => $avtoVodiy->id,
                'name' => $avtoVodiy->name,
                'phone' => $avtoVodiy->phone,
                'avatar_url' => $avtoVodiy->avatar_url,
            ];
            $data = array_filter($data, fn ($u) => $u['id'] !== $avtoVodiy->id);
            array_unshift($data, $avtoVodiyItem);
        }

        return response()->json(['data' => array_values($data)]);
    }
}

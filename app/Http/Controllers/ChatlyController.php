<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\StoreChatMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChatlyController extends Controller
{
    private const GLOBAL_ROOM_KEY = 'global';
    private const GLOBAL_ROOM_NAME = 'Global Room';

    public function index(): View
    {
        $users = User::where('id', '!=', Auth::id())->get();
        if (Auth::check()) {
            User::whereKey(Auth::id())->update(['last_seen' => now()]);
        }
        $globalRoom = [
            'key' => self::GLOBAL_ROOM_KEY,
            'name' => self::GLOBAL_ROOM_NAME,
        ];
        return view('Chatly', ['users' => $users, 'globalRoom' => $globalRoom]);
    }

    public function store(StoreChatMessageRequest $request, User $user): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $messageText = $validated['message'] ?? '';

        $filePath = null;

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('message_files', 'public');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $messageText,
            'file_path' => $filePath,
        ]);
        $message->loadMissing('sender');

        // Broadcast the message event
        broadcast(new MessageSent(message: $message))->toOthers();

        // If this is not an AJAX request, redirect back to prevent raw JSON display
        if (! $request->ajax()) {
            return back();
        }

        // Return only the necessary data for AJAX requests
        return response()->json([
            'success' => true,
            'message' => $this->messagePayload($message),
        ]);
    }

    public function room(string $roomKey): View
    {
        if ($roomKey !== self::GLOBAL_ROOM_KEY) {
            abort(404);
        }

        $messages = Message::where('room_key', $roomKey)
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('components.chat-room', [
            'roomKey' => $roomKey,
            'roomName' => self::GLOBAL_ROOM_NAME,
            'messages' => $messages,
        ]);
    }

    public function storeRoom(StoreChatMessageRequest $request, string $roomKey): JsonResponse|RedirectResponse
    {
        if ($roomKey !== self::GLOBAL_ROOM_KEY) {
            abort(404);
        }

        $validated = $request->validated();
        $messageText = $validated['message'] ?? '';

        $filePath = null;

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('message_files', 'public');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => Auth::id(),
            'room_key' => $roomKey,
            'message' => $messageText,
            'file_path' => $filePath,
        ]);
        $message->loadMissing('sender');

        // Broadcast the message event
        broadcast(new MessageSent(message: $message))->toOthers();

        // If this is not an AJAX request, redirect back to prevent raw JSON display
        if (! $request->ajax()) {
            return back();
        }

        // Return only the necessary data for AJAX requests
        return response()->json([
            'success' => true,
            'message' => $this->messagePayload($message),
        ]);
    }

    public function chat(User $user): View
    {
        // Mark messages from this user to the authenticated user as read when opening chat
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        })->get();

        return view('components.chat-area', ['user' => $user, 'messages' => $messages]);
    }

    private function messagePayload(Message $message): array
    {
        $sender = $message->sender;
        $senderAvatar = null;
        if ($sender && $sender->profile_image) {
            $senderAvatar = Storage::url($sender->profile_image);
        }

        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'room_key' => $message->room_key,
            'sender_name' => $sender?->name,
            'sender_avatar' => $senderAvatar,
            'message' => $message->message,
            'file_path' => $message->file_path ? Storage::url($message->file_path) : null,
            'created_at' => $message->created_at->toDateTimeString(),
        ];
    }
}

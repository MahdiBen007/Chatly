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
    public function index(): View
    {
        $users = User::where('id', '!=', Auth::id())->get();
        if (Auth::check()) {
            User::whereKey(Auth::id())->update(['last_seen' => now()]);
        }
        return view('Chatly', ['users' => $users]);
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

        // Broadcast the message event
        broadcast(new MessageSent(message: $message))->toOthers();

        // If this is not an AJAX request, redirect back to prevent raw JSON display
        if (! $request->ajax()) {
            return back();
        }

        // Return only the necessary data for AJAX requests
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'file_path' => $message->file_path ? Storage::url($message->file_path) : null,
                'created_at' => $message->created_at->toDateTimeString(),
            ]
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
}

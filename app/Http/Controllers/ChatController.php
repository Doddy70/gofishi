<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * User Inbox Logic
     */
    public function userInbox()
    {
        $user = Auth::guard('web')->user();
        $chats = Chat::with(['vendor', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])->where('user_id', $user->id)->latest()->get();

        // Needs a UI view
        return view('frontend.user.chat-inbox', compact('chats'));
    }

    /**
     * Initiate a chat from a Room/Perahu details page (AJAX supported)
     */
    public function initiateChat(Request $request, $vendor_id)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json(['status' => 'unauthorized', 'message' => __('Silakan login untuk mengirim pesan.')], 401);
        }

        $user = Auth::guard('web')->user();
        $room_id = $request->room_id;

        // Check if a chat already exists between this user and vendor
        $chat = Chat::firstOrCreate([
            'user_id' => $user->id,
            'vendor_id' => $vendor_id,
        ], [
            'room_id' => $room_id // Only stored on creation
        ]);

        // If there's a message in the request, save it
        if ($request->filled('message')) {
            ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_type' => 'user',
                'message' => $request->message,
            ]);
            $chat->touch();
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Pesan terkirim!'),
                'chat_id' => $chat->id
            ]);
        }

        return redirect()->route('user.chat.inbox', ['chat_id' => $chat->id]);
    }

    /**
     * Fetch messages (AJAX/Polling)
     */
    public function fetchMessages($chat_id)
    {
        // Authenticate the user is part of the chat
        $user = Auth::guard('web')->user();
        $chat = Chat::where('id', $chat_id)->where('user_id', $user->id)->firstOrFail();

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        // Mark vendor messages as read
        ChatMessage::where('chat_id', $chat_id)
            ->where('sender_type', 'vendor')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send message (AJAX)
     */
    public function sendMessage(Request $request, $chat_id)
    {
        $request->validate(['message' => 'required|string']);

        $user = Auth::guard('web')->user();
        $chat = Chat::where('id', $chat_id)->where('user_id', $user->id)->firstOrFail();

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'user',
            'message' => $request->message,
        ]);

        // Trigger latest timestamp update on Chat for sorting inbox
        $chat->touch();

        return response()->json(['status' => 'success', 'message' => $message]);
    }
}
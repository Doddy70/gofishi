<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorChatController extends Controller
{
    /**
     * Vendor Inbox Logic
     */
    public function vendorInbox()
    {
        $vendor = Auth::guard('vendor')->user();
        $chats = Chat::with(['user', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])->where('vendor_id', $vendor->id)->latest()->get();

        // Needs a UI view
        return view('vendor.chat-inbox', compact('chats'));
    }

    /**
     * Fetch messages (AJAX/Polling)
     */
    public function fetchMessages($chat_id)
    {
        $vendor = Auth::guard('vendor')->user();
        $chat = Chat::where('id', $chat_id)->where('vendor_id', $vendor->id)->firstOrFail();

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        // Mark user messages as read
        ChatMessage::where('chat_id', $chat_id)
            ->where('sender_type', 'user')
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

        $vendor = Auth::guard('vendor')->user();
        $chat = Chat::where('id', $chat_id)->where('vendor_id', $vendor->id)->firstOrFail();

        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'vendor',
            'message' => $request->message,
        ]);

        // Trigger latest timestamp update on Chat for sorting inbox
        $chat->touch();

        return response()->json(['status' => 'success', 'message' => $message]);
    }
}
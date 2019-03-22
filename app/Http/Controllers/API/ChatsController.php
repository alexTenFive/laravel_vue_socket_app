<?php

namespace App\Http\Controllers\API;

use App\Models\Message;
use App\Events\MessagePushed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatsController extends Controller
{
    public function fetchMessages()
    {
        return response()->json([
            'data' => Message::with('user')->get(),
            'status' => 200,
        ], 200);
    }

    public function sendMessage(Request $request)
    {
        $user = auth()->user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);
        
        broadcast(new MessagePushed($user, $message))->toOthers();

        return response()->json([
            'data'   => $message,
            'status' => 201,
        ], 201);
    }
}

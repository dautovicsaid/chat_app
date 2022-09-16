<?php

namespace App\Http\Controllers;

use App\Events\NewMessageEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;


class MessageController extends Controller
{
    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
        $message = Message::create([
            'created_by' => auth()->id(),
            'content' => $request->message_content,
            'conversation_id' => $conversation->id
        ]);

        event(new NewMessageEvent($message));

        return $message;
    }

}

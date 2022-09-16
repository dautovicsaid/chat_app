<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{

    public function index()
    {

        $conversations = $this->get_conversations_data();


        return view('conversations.index', [
            "conversations" => $conversations
        ]);
    }


    public function message_history(Conversation $conversation)
    {
        if ($conversation->friendship->status != "accepted")
            return redirect('conversations.index');

        $conversations = $this->get_conversations_data();

        $messages = Message::query()->select('messages.*',
            DB::raw("DATE_FORMAT(messages.created_at, '%H:%i | %d %M') as created_at_formatted"))
            ->join('conversations', 'messages.conversation_id', 'conversations.id')
            ->where('conversation_id', '=', $conversation->id)
            ->orderBy('messages.created_at')
            ->get();

        return view('conversations.messages_history', [
            "conversations" => $conversations,
            "current_conversation" => $conversation->id,
            "messages" => $messages
        ]);
    }

    private function get_conversations_data()
    {
        $user_id = auth()->id();

        $sub_query = DB::query()
            ->select(DB::raw('messages.id'))
            ->from('messages')
            ->where('conversation_id', '=', DB::raw('conv.id'))
            ->orderBy('messages.created_at', 'desc')
            ->limit(1);

        $data = DB::query()
            ->select('conv.*',
                DB::raw("IF($user_id = fs.requested_by,u2.name,u1.name) as friend_name"),
                DB::raw("IF($user_id = fs.requested_by,u2.profile_picture_path,u1.profile_picture_path) as friend_profile_picture_path"),
                'msg.created_at', 'msg.created_by', 'msg.content as last_message')
            ->from('conversations', 'conv')
            ->leftJoin('messages as msg', 'msg.id', DB::raw("({$sub_query->toSql()})"))
            ->join('friendships as fs', 'conv.friendship_id', 'fs.id')
            ->join('users as u1', 'fs.requested_by', 'u1.id')
            ->join('users as u2', 'fs.target_user', 'u2.id')
            ->where('fs.status', 'accepted')
            ->where(function ($query) use ($user_id) {
                $query->orWhere('requested_by', '=', $user_id)
                    ->orWhere('target_user', $user_id);
            })->orderBy('msg.created_at', 'desc')->get();

        return $data;
    }
}

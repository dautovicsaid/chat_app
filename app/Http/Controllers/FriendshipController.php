<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFriendshipRequest;
use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FriendshipController extends Controller
{


    public function get_friends()
    {

        $user_id = auth()->id();

        $friendships = DB::query()
            ->select('fs.*', 'conv.id as conversation_id', DB::raw("IF($user_id = fs.requested_by,u2.name,u1.name) as friend_name"),
                DB::raw("IF($user_id = fs.requested_by,u2.id,u1.id) as friend_id"),
                DB::raw("IF($user_id = fs.requested_by,u2.profile_picture_path,u1.profile_picture_path) as friend_profile_picture_path"))
            ->from('friendships', 'fs')
            ->join('users as u1', 'fs.requested_by', 'u1.id')
            ->join('users as u2', 'fs.target_user', 'u2.id')
            ->join('conversations as conv', 'fs.id', 'conv.friendship_id')
            ->where(function ($query) use ($user_id) {
                $query->where('requested_by', '=', $user_id)
                    ->orWhere('target_user', '=', $user_id);
            }
            )->where('status', '=', 'accepted')->get();

        return view('friendships.friends', [
            "friendships" => $friendships
        ]);
    }


    public function add_friend(StoreFriendshipRequest $request)
    {
        $user_id = auth()->id();

        $target_user = $request->target_user;

        $count = Friendship::query()->where('status', '!=', 'declined')
            ->where(function ($query) use ($target_user, $user_id) {
                $query->where(function ($query) use ($target_user, $user_id) {
                    $query->where('requested_by', '=', $user_id)->where('target_user', '=', $target_user);
                })
                    ->orWhere(function ($query) use ($target_user, $user_id) {
                        $query->where('requested_by', '=', $target_user)->where('target_user', '=', $user_id);
                    });
            })
            ->count();


        if ($count > 0) return redirect()->route('home')->withErrors(["msg" => "You have already added that user"]);

        Friendship::query()->create(
            ['target_user' => $target_user,
                'requested_by' => $user_id]
        );

        return redirect()->route('home');

    }


    public function get_friend_requests()
    {
        $user = auth()->id();
        $friend_requests = User::query()
            ->select(['friendships.id', 'users.name', 'users.id as user_id', 'users.profile_picture_path'])
            ->join('friendships', 'friendships.requested_by', '=', 'users.id')
            ->where('friendships.target_user', '=', $user)
            ->where('friendships.status', '=', 'pending')
            ->get();

        return view('friendships.friend_requests', [
            'friend_requests' => $friend_requests
        ]);
    }


    public function accept_friend_request(Friendship $friendship)
    {

        $friendship->status = 'accepted';
        $friendship->save();
        Conversation::create([
            'friendship_id' => $friendship->id
        ]);

        return redirect()->route('get_friend_requests');

    }

    public function decline_friend_request(Friendship $friendship)
    {

        $friendship->status = 'declined';
        $friendship->save();

        return redirect()->route('get_friend_requests');

    }


    public function cancel_friend_request(Friendship $friendship)
    {

        $friendship->delete();

        return redirect()->back();

    }

    public function unfriend(Friendship $friendship)
    {
        $friendship->delete();

        return redirect()->route('home');

    }
}

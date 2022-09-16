<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = auth()->id();

        $suggested_friends = User::query()
            ->whereNotExists(function ($query) use ($user_id) {
                $query->select(DB::raw(1))
                    ->from('friendships', 'fs')
                    ->whereNot('fs.status', 'declined')
                    ->where(function ($query) use ($user_id) {
                        $query->Where(function ($query) use ($user_id) {
                            $query->where('fs.requested_by', '=', $user_id)
                                ->where('fs.target_user', '=', DB::raw('users.id'));
                        })->orWhere(function ($query) use ($user_id) {
                            $query->where('fs.target_user', '=', $user_id)
                                ->where('fs.requested_by', '=', DB::raw('users.id'));
                        });
                    });
            })->where(DB::raw('users.id'), '!=', $user_id)
            ->paginate(10);

        return view('home', [
            "users" => $suggested_friends
        ]);
    }
}

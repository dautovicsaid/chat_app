<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function show(User $user)
    {
        $current_user = auth()->id();
        $friendship = DB::query()
            ->select('fs.*', 'conv.id as conversation_id')
            ->from('friendships', 'fs')
            ->leftJoin('conversations as conv', 'conv.friendship_id', 'fs.id')
            ->where('status', '!=', 'declined')
            ->where(function ($query) use ($user, $current_user) {
                $query->where(function ($query) use ($user, $current_user) {
                    $query->where('requested_by', '=', $user->id)
                        ->where('target_user', '=', $current_user);
                })
                    ->orWhere(function ($query) use ($user, $current_user) {
                        $query->where('requested_by', '=', $current_user)
                            ->where('target_user', '=', $user->id);
                    });
            })
            ->get();

        return view('users.show', [
            "user" => $user,
            "friendship" => $friendship
        ]);
    }

    public function edit(User $user)
    {

        $this->is_auth_user($user);

        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

        $this->is_auth_user($user);

        if ($request->profile_picture == null) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email]);
            return redirect()->route('users.show', ['user' => $user]);

        }

        if ($user->profile_picture_path) {
            Storage::delete('images', $user->profile_picture_path);
        }

        $path = Storage::put('images', $request->profile_picture);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture_path' => $path
        ]);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function password_reset_edit(User $user)
    {

        if (auth()->user() != $user)
            return redirect('home');

        return view('users.password_reset', [
            'user' => $user
        ]);
    }

    public function password_reset_update(UpdateUserPasswordRequest $request, User $user)
    {
        $this->is_auth_user($user);

        $user->update([
            "password" => Hash::make($request->password)]);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function is_auth_user($user)
    {
        if (auth()->user() != $user)
            return redirect('home');
    }
}

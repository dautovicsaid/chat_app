<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function friendship()
    {
        return $this->belongsTo(Friendship::class);
    }

    public static function is_logged_in_users_message($id)
    {

        $user_id = auth()->id();

        return $user_id == $id;
    }
}

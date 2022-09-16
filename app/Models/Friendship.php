<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = ['requested_by', 'target_user'];
    public $timestamps = false;

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}

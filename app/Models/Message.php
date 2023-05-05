<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Community;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'user_id',
        'content',
    ];

    //ユーザーと多対1
    public function user () {
        return $this->belongsTo(User::class);
    }

    //コミュニティと多対1
    public function community () {
        return $this->belongsTo(Community::class);
    }
}

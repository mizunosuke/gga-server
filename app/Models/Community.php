<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'creator_user_id',
    ];

    //ユーザーと多対多
    public function users () {
        return $this->belongsToMany(User::class, 'community_user')->withPivot('community_id', 'user_id')->withTimestamps();
    }

    //メッセージと1対多
    public function messages () {
        return $this->hasMany(Message::class);
    }
}

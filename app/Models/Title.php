<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title_icon_path'
    ];

    //ユーザーと多対多
    public function user () {
        return $this->belongsToMany(User::class, 'title_user')->withPivot('title_id', 'user_id')->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Ranking extends Model
{
    use HasFactory;

    protected $fillable = [
        'fish_id',
        'start',
        'end',
        'participants',
    ];

    //ユーザーと多対多
    public function users () {
        return $this->belongsToMany(User::class, 'ranking_user')->withPivot('user_id', 'ranking_id')->withTimestamps();
    }
}

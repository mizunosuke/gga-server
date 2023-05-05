<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fish_id',
        'color',
        'area',
        'comment',
        'size',
        'rank',
        'points'
    ];

    //userと多対1
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //commentと1対多
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    //postと多対1
    public function post ()
    {
        return $this->belongsTo(Post::class);
    }

    //postと多対1
    public function user ()
    {
        return $this->belongsTo(User::class);
    }
    
}

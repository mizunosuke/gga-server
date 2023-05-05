<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Message;
use App\Models\Community;
use App\Models\Title;
use App\Models\Ranking;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'icon_path',
        'area',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //user->post(1対多)
    public function posts () {
        return $this->hasMany(Post::class);
    }

    //メッセージと1対多
    public function messages () {
        return $this->hasMany(Message::class);
    }

    //メッセージと1対多
    public function comments () {
        return $this->hasMany(Comment::class);
    }

    //user<->follower(多対多)
    public function followers () {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'follower_id');
    }

    //user<->following(多対多)
    public function following () {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'user_id');
    }

    //タイトルと多対多
    public function titles () {
        return $this->belongsToMany(Title::class, 'title_user')->withPivot('title_id', 'user_id')->withTimestamps();
    }

    //ランキングと多対多
    public function rankings () {
        return $this->belongsToMany(Ranking::class, 'ranking_user')->withPivot('user_id', 'ranking_id')->withTimestamps();
    }
    
    //コミュニティと多対多
    public function communities () {
        return $this->belongsToMany(Community::class, 'community_user')->withPivot('community_id', 'user_id')->withTimestamps();
    }
}

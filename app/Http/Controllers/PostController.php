<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Models\Fish;
use App\Models\Comment;
use App\Models\Ranking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'attachment' => 'required|string',
            'color' => 'required|string',
            'area' => 'required|string',
            'comment' => 'required|string',
            'size' => 'required|numeric',
            'day_of_fishing' => 'required',
            'fish_name' => 'required|string',
        ]);

        $dayOfFishing = Carbon::parse($validatedData['day_of_fishing']);

        // if ($dayOfFishing->isBefore(Carbon::now()->startOfWeek()) || $dayOfFishing->isAfter(Carbon::now()->endOfWeek())) {
        //     return response()->json([
        //         'message' => 'The fishing day is out of range for this week',
        //     ], 400);
        // }
    
        $fish = Fish::firstOrCreate(['name' => $validatedData['fish_name']]);
    
        // $fishを取得した後に、既に存在している場合は、それに関連するRankingのparticipantsをincrementする
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $ranking = Ranking::where('fish_id', $fish->id)
            ->where('start', '<=', $startOfWeek)
            ->where('end', '>=', $endOfWeek)
            ->first();

        //ランキングがなければ作成
        if (!$ranking) {
                // 当該週のRankingが存在しない場合、新規作成する
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $ranking = new Ranking();
                $ranking->fish_id = $fish->id;
                $ranking->start = $startOfWeek;
                $ranking->end = $endOfWeek;
                $ranking->participants = 1;
                $ranking->save();
                $ranking->users()->attach($validatedData['user_id']);
        } else {
            $ranking->increment('participants');
            $ranking->save();
            $ranking->users()->attach($validatedData['user_id']);
        }
    
        $post = new Post();
        $post->user_id = $validatedData['user_id'];
        $post->fish_id = $fish->id;
        $post->attachment = $validatedData['attachment'];
        $post->color = $validatedData['color'];
        $post->comment = $validatedData['comment'];
        $post->area = $validatedData['area'];
        $post->size = $validatedData['size'];
        $post->day_of_fishing = $validatedData['day_of_fishing'];
        $post->save();
    
        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $posts = Post::where("fish_id", $id)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->orderBy('size', 'desc')->get();
        foreach($posts as $post) {
            $user = User::find($post->user_id);
            $post->user = $user;
        }
        return $posts;
    }

    public function comments (Request $request, int $id) 
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|integer',
        ]);
        
        $comment = new Comment();
        $comment->post_id = $id;
        $comment->user_id = $validatedData['user_id'];
        $comment->content = $validatedData['content'];
        $comment->save();

        return response()->json(['comment' => $comment], 201);
    }

    public function getcomments (Request $request, int $id) 
    {
         // ポストの詳細情報を取得する
         $post = Post::find($id);

         // ポストに関連するコメントを取得する
        $comments = Comment::where('post_id', $post->id)->with('user')->get();
        foreach ($comments as $comment) {
            $comment->user; // ユーザー情報が取得されるため、ここでの代入は不要
        }
        return response()->json(['comment' => $comments], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}

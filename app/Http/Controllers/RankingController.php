<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRankingRequest;
use App\Http\Requests\UpdateRankingRequest;
use App\Models\Ranking;
use App\Models\Fish;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;

class RankingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();
        //期間内のランキングデータ一覧を取得
        $allranking = Ranking::wherebetween("created_at",[$start, $end])->get();
        // ランキングデータに魚の名前を追加
        foreach ($allranking as $ranking) {
            $fish = Fish::find($ranking->fish_id);
            $ranking->fish_name = $fish->name;
            $ranking->fish_temperature = $fish->temperature;
        }

        //ランキングデータに投稿データを3件追加
        foreach ($allranking as $ranking) {
            $posts = Post::where("fish_id", "=", $ranking->fish_id)->whereBetween("created_at", [$start, $end])->orderBy("size", "desc")->take(3)->get();
            $ranking->posts = $posts;
        }

        //ランキングデータ内の取得した投稿のユーザー情報を取得
        foreach ($allranking as $ranking) {
            $ranking_posts = $ranking->posts;
            foreach ($ranking_posts as $post) {
                $user = User::where("id", "=", $post->user_id)->get();
                $post->user = $user;
            }
        }

        return $allranking;
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
     * @param  \App\Http\Requests\StoreRankingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRankingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function show(Ranking $ranking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function edit(Ranking $ranking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRankingRequest  $request
     * @param  \App\Models\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRankingRequest $request, Ranking $ranking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ranking $ranking)
    {
        //
    }
}

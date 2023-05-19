<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowRequest;
use App\Http\Requests\UpdateFollowRequest;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{

    public function follow(Request $request, $id) 
    {
        $loginUser = User::find($request->id);
        $followUser = User::find($id);
    
        if (!$loginUser || !$followUser) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // フォローがすでに存在しない場合にのみフォローする
        if (!$loginUser->following()->where('user_id', $followUser->id)->exists()) {
            $loginUser->following()->attach($followUser->id);
        }
    
        return response()->json(['message' => 'Successfully followed user.'], 200);
    }

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
     * @param  \App\Http\Requests\StoreFollowRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFollowRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // ユーザーを取得
        $user = User::findOrFail($id);

        // 相互フォロー関係にあるユーザーを取得
        $mutualFollowers = $user->followers()->whereHas('following', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        foreach ($mutualFollowers as $follower) {
            $follower->selected = false;
        }

        // レスポンスを返す
        return response()->json($mutualFollowers);

    }

    public function confirmfollow (Request $request, $id) {

        // ログイン中のユーザーを取得
        $currentUser = User::find($request->currentUserId);
        
        // ユーザーページのユーザーを取得
        $user = User::find($id);
        
        // ログイン中のユーザーがユーザーページのユーザーをフォローしているかを確認
        $isFollowing = $currentUser->following()->where('user_id', $user->id)->exists();
        
        return response()->json(['follow' => $isFollowing]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFollowRequest  $request
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFollowRequest $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Follow $follow)
    {
        //
    }
}

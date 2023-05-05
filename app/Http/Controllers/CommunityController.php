<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommunityRequest;
use App\Http\Requests\UpdateCommunityRequest;
use App\Models\Community;
use App\Models\Message;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $communities = Community::whereHas('users', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->get();
        
        return $communities;
    }

    public function postmessage (Request $request, int $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        $message = new Message;
        $message->community_id = $id;
        $message->user_id = $validatedData["user_id"];
        $message->content = $validatedData["content"];
        $message->save();

        return response()->json(['success' => true]);
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
     * @param  \App\Http\Requests\StoreCommunityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'groupName' => 'required|string',
            'groupDescription' => 'nullable|string',
            'creator_id' => 'required|integer',
        ]);

        $selectedFollowers = $request->selectedFollowers;

        $userIds = [$request->creator_id];

        foreach($selectedFollowers as $follower) {
            $userIds[] = $follower['id'];
        }

        $community = new Community;
        $community->name = $validatedData["groupName"];
        $community->description = $validatedData["groupDescription"];;
        $community->creator_user_id = $validatedData["creator_id"];; // ログインしているユーザーを作成者とする
        $community->save();
    
        $communityId = $community->id;
        // 中間テーブルに選択されたユーザーを追加
        $community->users()->sync($userIds, ["community_id" => $communityId]);
    
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $communityId = $id;
        $messages = Message::where('community_id', $communityId)->get();
        // return response()->json(['id' => $message->id, 'user_id' => $message->user_id, 'text' => $message->content]);
        return $messages;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function edit(Community $community)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommunityRequest  $request
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommunityRequest $request, Community $community)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Community $community)
    {
        //
    }
}

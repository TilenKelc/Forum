<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Log;

class FriendController extends Controller
{
    public function followUser(Request $request){
        $request->validate([
            'friend_id' => ['required'],
        ]);

        $friend = new Friend();
        $friend->friend_id = $request->friend_id;
        $friend->user_id = Auth::id();
        $friend->save();

        return;
    }

    public function unfollowUser(Request $request){
        $request->validate([
            'friend_id' => ['required'],
        ]);

        $friend = Friend::where('user_id', Auth::id())->where('friend_id', $request->friend_id)->get();
        $friend->delete();
        
        return;
    }    
}

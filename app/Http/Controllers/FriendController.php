<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Friend;
use App\Models\User;

use Log;

class FriendController extends Controller
{
    public function followUser(Request $request){
        $user = User::find($request->id);

        $friend = new Friend();
        $friend->friend_id = $request->id;
        $friend->user_id = Auth::id();
        $friend->save();

        session(['successMssg' => "You are now following $user->username"]);
        return back();
    }

    public function unfollowUser(Request $request){
        $user = User::find($request->id);

        $friend = Friend::where('user_id', Auth::id())->where('friend_id', $request->id)->first();
        $friend->delete();
        
        session(['successMssg' => "You stoped following $user->username"]);
        return back();
    }    
}

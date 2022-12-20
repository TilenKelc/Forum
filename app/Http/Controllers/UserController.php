<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Post;
use App\Models\Friend;
use DataTables;

class UserController extends Controller
{
    public function showProfile(){
        return view('user.edit', [
            "user" => Auth::user()
        ]);
    }

    public function showUserInfo(Request $request){
        $user = User::find($request->id);
        $posts = Post::where('user_id', $user->id)->get();
        $following_friends = Friend::where('user_id', $user->id)->get();
        $followers_friends = Friend::where("friend_id", $user->id)->get();

        return view("user.show", [
            "user" => $user,
            "posts" => $posts,
            "count_following" => count($following_friends),
            "count_followers" => count($followers_friends)
        ]);
    }

    public function saveUpdatedUser(Request $request){
        $user = User::find(Auth::id());

        if($request->password != null || $request->password_confirmation != null){
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
        }
        
        if($request->username != Auth::user()->username){
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
            ]);
            $user->username = $request->username;
        }
        $user->save();

        session(['successMssg' => 'Profile successfully updated']);
        return back();
    }

    // Admin options

    public function showUsers(){
        return view('admin.users');
    }

    public function fetchUsers(Request $request){
        $user = User::all();
        return Datatables::of($user)
            ->editColumn('username', function($user){
                return '<th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                    ' . $user->username. '</th>';
            })
            ->editColumn('privileges', function($user){
                if($user->isAdmin()){
                    return '<td class="px-6 py-4">Admin</td>';
                }else{
                    return '<td class="px-6 py-4">User</td>';
                }
            })
            ->editColumn('email', function($user){
                return '<td class="px-6 py-4">' . $user->email  . '</td>';
            })
            ->editColumn('deleted', function($user){
                if($user->deleted){
                    return '<td class="px-6 py-4">Yes (' . $user->deleted_at . ')</td>';
                }else{
                    return '<td class="px-6 py-4">No</td>';
                }
            })
            ->addColumn('block', function($user){
                if($user->deleted){
                    return '<td class="px-6 py-4">' . "<a href='" . url("/admin/user/unblock/$user->id") . "'>Unblock</a></td>";
                }else{
                    return '<td class="px-6 py-4">' . "<a href='" . url("/admin/user/block/$user->id") . "'>Block</a></td>";
                }
            })
            ->rawColumns(['username', 'privileges', 'deleted', 'email', 'block'])
            ->make(true);
    }

    public function blockUser(Request $request){
        $user = User::find($request->id);
        $user->deleted = true;
        $user->deleted_at = date('Y-m-d h:i.s');
        $user->save();

        return back();
    }
    
    public function unblockUser(Request $request){
        $user = User::find($request->id);
        $user->deleted = false;
        $user->deleted_at = NULL;
        $user->save();

        return back();
    }

    /** --------------------------- Api ------------------ */

    public function registerApi(Request $request){
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function loginApi(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function getProfileApi(Request $request){
        return response()->json([
            'status' => 200, 
            'data' => $request->user()
        ]);        
    }

    public function getUserInfoApi(Request $request){
        $user = User::find($request->id);
        return response()->json(["status" => 200, "data" => $user]);
    }

    public function saveUpdatedUserApi(Request $request){
        $user = $request->user();

        if($request->password != null || $request->password_confirmation != null){
            $request->validate([
                'password' => ['required', Rules\Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
        }
        
        if($request->username != Auth::user()->username){
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
            ]);
            $user->username = $request->username;
        }
        $user->save();

        return response()->json(['status' => 200]);
    }
}

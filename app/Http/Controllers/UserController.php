<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use DataTables;

class UserController extends Controller
{
    /*public function showLogin(){
        return view('login.blade.php');
    }*/

    public function showProfile(){
        return view('user.edit', [
            "user" => Auth::user()
        ]);
    }

    public function showUserInfo(Request $request){
        $user = User::find($request->id);

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
}

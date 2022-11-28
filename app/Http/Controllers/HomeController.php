<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    public function showHome(){
        $posts = Post::where('deleted', 'false')->orderBy('created_at', 'desc')->get();
        return view('home', [
            "posts" => $posts,
        ]);
    }
}

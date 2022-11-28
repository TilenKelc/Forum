<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    public function getUser(){
        $user = User::find($this->user_id);
        return $user;
    }
}

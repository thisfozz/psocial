<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function search(Request $request){
        $search = trim($request->input('search'));
        if(empty($search)){
            return response()->json([]);
        }
        $words = preg_split('/\s+/', $search);

        if(count($words) === 1){
            $users = User::where('first_name', 'like', '%'.ucfirst($words[0]).'%')
                ->orWhere('last_name', 'like', '%'.ucfirst($words[0]).'%')
                ->get();
        }
        elseif(count($words) === 2){
            $users = User::where(function($query) use ($words){
                $query->where('first_name', 'like', '%'.ucfirst($words[0]).'%')
                    ->where('last_name', 'like', '%'.ucfirst($words[1]).'%');
            })->orWhere(function($query) use ($words){
                $query->where('last_name', 'like', '%'.ucfirst($words[0]).'%')
                    ->where('first_name', 'like', '%'.ucfirst($words[1]).'%');
            })->get();
        }
        else{
            $users = [];
        }
        // TODO: Изменить на редирект на страницу пользователя
        return response()->json($users);
    }

    public function userOnlineStatus()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (Cache::has('user-online' . $user->id))
                $user->last_seen = now();
            else
                echo $user->name . " is offline <br>";
        }
    }
}
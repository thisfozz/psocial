<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
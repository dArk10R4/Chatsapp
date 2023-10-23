<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
class UserController extends Controller
{
    //
    public function getUsers(Request $request) {
        
        $users = User::select('id', 'name')->where('id', '!=', $request->user()->id)->get();
        return response()->json($users,200);
    }
    public function getMessages(Request $request)
    {
        $id = $request->user()->id;
        $messages = Message::select('messages.*', 'users.name as name')
        ->leftJoin('users', 'messages.from', '=', 'users.id')
        ->where(function ($query) use ($id) {
            $query->where('messages.to', $id)
                ->orWhere('messages.from', $id);
        })
        ->orderBy('messages.created_at', 'asc') 
        ->get()
        ->groupBy(function ($message) use ($id) {
            return $message->to != $id ? $message->to : $message->from;
        });;
        return response()->json(
            [
                "messages"=>$messages
            ]
            ,200);
    }
}

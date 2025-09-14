<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function teacherChat()
    {
        // Load messages between this teacher and some student(s)
        // Example: with one student or choose student from list
        // For simplicity, assume chatting with all messages
        $messages = Message::where(function($q){
            $q->where('from_user_type', 'teacher')
              ->where('from_user_id', auth()->guard('teacher')->id());
        })->orWhere(function($q){
            $q->where('to_user_type', 'teacher')
              ->where('to_user_id', auth()->guard('teacher')->id());
        })->orderBy('created_at')->get();

        return view('teacher.chat', compact('messages'));
    }

    public function studentChat()
    {
        $messages = Message::where(function($q){
            $q->where('from_user_type', 'student')
              ->where('from_user_id', auth()->guard('student')->id());
        })->orWhere(function($q){
            $q->where('to_user_type', 'student')
              ->where('to_user_id', auth()->guard('student')->id());
        })->orderBy('created_at')->get();

        return view('student.chat', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'to_id' => 'required|integer',
            'to_type' => 'required|string|in:teacher,student',
        ]);

        // Determine from_type and from_id
        if (auth()->guard('teacher')->check()) {
            $from_type = 'teacher';
            $from_id = auth()->guard('teacher')->id();
        } elseif (auth()->guard('student')->check()) {
            $from_type = 'student';
            $from_id = auth()->guard('student')->id();
        } else {
            abort(403);
        }

        $msg = Message::create([
            'from_user_id' => $from_id,
            'from_user_type' => $from_type,
            'to_user_id' => $request->to_id,
            'to_user_type' => $request->to_type,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        return response()->json([
            'status' => 'ok',
            'message' => $msg
        ]);
    }
}

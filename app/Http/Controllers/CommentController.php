<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\NewComment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return response()->json($post->comments()->with('user')->latest()->get());
        // return $post->comments()->with('user')->latest()->get();
    }
    public function delete($id)
    {
        Comment::find($id)->delete();

    }

    public function update($id , Request $request)
    {
       Comment::find($id)->update([
            'body' => $request->body
        ]);
        $comment = Comment::find($id);
       return $comment;
    }

    public function store(Request $request, Post $post)
    {

        $comment = $post->comments()->create([
            'body'    => $request->body,
            'user_id' => auth()->user()->id,
        ]);
        $comment = Comment::where('id', $comment->id)->with('user')->first();
        broadcast(new NewComment($comment))->toOthers();

        return $comment->toJson();
    }
}

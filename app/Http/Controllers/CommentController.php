<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\DeleteComment;
use App\Events\NewComment;
use App\Events\UpdateComment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return response()->json($post->comments()->with(['user', 'children', 'parent', 'children.user'])->latest()->get());
        // return $post->comments()->with('user')->latest()->get();
    }

    public function reply(Comment $comment, Request $request)
    {

        $data = $comment->children()->create([
            'body'      => $request->body,
            'parent_id' => $comment->id,
            'post_id'   => $comment->post->id,
            'user_id'   => auth()->user()->id,
        ]);

        $response = $comment->children()->where('id', $data->id)->with('user')->first();

        return $response;
    }

    public function delete($id)
    {
        DB::table('comments')->where('id', $id)->orWhere('parent_id', $id)->delete();
        broadcast(new DeleteComment($id))->toOthers();
    }

    public function update($id, Request $request)
    {
        $comment       = Comment::find($id);
        $comment->body = $request->body;
        $comment->save();
        broadcast(new UpdateComment($comment));

        return $comment->toJson();
    }

    public function store(Request $request, Post $post)
    {

        $comment = $post->comments()->create([
            'body'    => $request->body,
            'user_id' => auth()->user()->id,
        ]);
        $comment = Comment::where('id', $comment->id)->with(['user', 'children'])->first();

        // broadcast(new NewComment($comment))->toOthers();
        return $comment->toJson();
    }
}

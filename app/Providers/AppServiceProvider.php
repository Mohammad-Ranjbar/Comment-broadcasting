<?php

namespace App\Providers;

use App\Comment;
use App\Events\NewComment;
use Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }


    public function boot()
    {
        // Comment::created(function ($comment) {
        //     broadcast(new NewComment($comment))->toOthers();
        //     return $comment->toJson();
        // });
    }
}

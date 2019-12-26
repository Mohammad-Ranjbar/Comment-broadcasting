<?php

namespace App\Providers;

use App\Comment;
use App\Events\DeleteComment;
use App\Events\NewComment;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Comment::created(function ($comment) {
            broadcast(new NewComment($comment))->toOthers();

        });

        // Comment::deleted(function ($comment) {
        //     broadcast(new DeleteComment($comment))->toOthers();
        // });
    }
}

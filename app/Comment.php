<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body', 'user_id', 'post_id'
    ];


    public function parent()
    {
        return $this->belongsTo(self::class,'id','parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id','id');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}

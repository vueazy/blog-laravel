<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected static function booted()
    {
        static::creating(function ($comment) {
            $comment->author_name = $comment->author_name ?? Auth::user()->name;
            $comment->user_id = Auth::id();
        });
    }
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $guarded = ['id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected static function booted()
    {
        static::creating(function ($post) {
            $post->slug = Str::slug(Str::lower($post->title));
            $post->user_id = Auth::id();
        });

        static::updating(function ($post) {
            $post->slug = Str::slug(Str::lower($post->title));
        });
    }

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

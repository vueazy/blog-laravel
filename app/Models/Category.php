<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug(Str::lower($category->name));
        });

        static::updating(function ($category) {
            $category->slug = Str::slug(Str::lower($category->name));
        });
    }

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Summary of posts
     * 
     * @return HasMany<Post, Category>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

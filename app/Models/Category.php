<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
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

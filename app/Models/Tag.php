<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected static function booted()
    {
        static::creating(function ($tag) {
            $tag->slug = Str::slug(Str::lower($tag->name));
        });

        static::updating(function ($tag) {
            $tag->slug = Str::slug(Str::lower($tag->name));
        });
    }
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $guarded = ['id'];
}

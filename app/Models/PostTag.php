<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'post_tags';
}

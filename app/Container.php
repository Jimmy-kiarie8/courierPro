<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Container extends Model
{
	use SoftDeletes;
    protected $casts = [
        'selected' => 'array',
    ];
}

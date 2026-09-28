<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeldTransaction extends Model
{
    protected $fillable = ['user_id', 'label', 'payload'];

    protected $casts = ['payload' => 'array'];
}

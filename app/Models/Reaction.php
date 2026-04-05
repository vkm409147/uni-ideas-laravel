<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = ['user_id', 'idea_id', 'type'];
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}

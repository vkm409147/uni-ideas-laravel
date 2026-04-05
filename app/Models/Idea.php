<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'user_id',
        'department_id',
        'file_path',
        'is_anonymous',
    ];
public function user() {
    return $this->belongsTo(User::class);
}

public function category() {
    return $this->belongsTo(Category::class);
}
public function reactions()
{
    return $this->hasMany(\App\Models\Reaction::class);
}

public function comments() {
    return $this->hasMany(Comment::class)->latest();
}

// Hàm tính tổng điểm (Popularity)
public function getPopularityAttribute()
{
    return $this->reactions()->sum('type');
}
}

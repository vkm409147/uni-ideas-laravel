<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $table = 'categories'; // Tên bảng trong DB của bạn
    protected $fillable = [
    'name', 
    'closure_date', 
    'final_closure_date'
];
    protected $casts = [
    'closure_date' => 'datetime',
    'final_closure_date' => 'datetime',
];
public function ideas()
{
    return $this->hasMany(Idea::class);
}
}

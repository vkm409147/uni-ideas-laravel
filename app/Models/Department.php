<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // Khai báo tên bảng nếu tên bảng của bạn không phải là "departments"
    protected $table = 'departments';

    // Các cột có thể nạp dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = ['name'];

    // Thiết lập quan hệ: Một phòng ban có nhiều người dùng
    public function users()
    {
        return $this->hasMany(User::class);
    }

public function ideas()
{
    // Quan hệ xuyên suốt: Department -> User -> Idea
    return $this->hasManyThrough(Idea::class, User::class);
}
}
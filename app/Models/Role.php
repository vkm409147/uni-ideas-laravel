<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Khai báo bảng nếu tên bảng của bạn không phải là 'roles'
    protected $table = 'roles';

    // Cho phép lấy danh sách user thuộc role này (nếu cần)
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    // Cần khai báo cái này nếu bảng không có cột 'id' tự tăng
    protected $primaryKey = 'key'; 
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];
}

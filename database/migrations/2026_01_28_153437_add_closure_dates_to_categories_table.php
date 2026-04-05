<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('categories', function (Blueprint $table) {
        // Kiểm tra nếu cột chưa tồn tại thì mới thêm (để tránh lỗi)
        if (!Schema::hasColumn('categories', 'closure_date')) {
            $table->dateTime('closure_date')->nullable();
        }
        if (!Schema::hasColumn('categories', 'final_closure_date')) {
            $table->dateTime('final_closure_date')->nullable();
        }
    });
}

public function down()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn(['closure_date', 'final_closure_date']);
    });
}
};

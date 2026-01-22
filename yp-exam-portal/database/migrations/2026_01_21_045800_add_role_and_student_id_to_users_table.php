<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_role_and_student_id_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->string('student_id')->nullable()->after('role'); // For students
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([ 'student_id']);
        });
    }
};
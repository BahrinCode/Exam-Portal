<?php
// database/migrations/xxxx_xx_xx_xxxxxx_fix_class_subject_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First, check if the table exists and has wrong columns
        if (Schema::hasTable('class_subject')) {
            // Check what columns exist
            $columns = Schema::getColumnListing('class_subject');
            
            // If wrong column exists, we need to fix it
            if (in_array('class_model_id', $columns)) {
                // Drop the table and recreate it correctly
                Schema::dropIfExists('class_subject');
                
                Schema::create('class_subject', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('class_id')->constrained()->onDelete('cascade');
                    $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                    $table->timestamps();
                    
                    $table->unique(['class_id', 'subject_id']);
                });
            }
        }
    }

    public function down(): void
    {
        // Since we're fixing a broken table, the down method should be careful
        // We'll just leave it as is
    }
};
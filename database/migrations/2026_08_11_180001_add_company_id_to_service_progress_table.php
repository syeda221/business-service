<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_progress', function (Blueprint $table) {
            // First add the new company_id column
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            
            // Create a temporary index on user_id so MySQL allows dropping the unique key
            $table->index('user_id');
            
            // Drop unique key
            $table->dropUnique(['user_id', 'service_key']);
            
            // Add new unique key including company_id
            $table->unique(['user_id', 'service_key', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_progress', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'service_key', 'company_id']);
            $table->unique(['user_id', 'service_key']);
            $table->dropIndex(['user_id']);
            $table->dropConstrainedForeignId('company_id');
        });
    }
};

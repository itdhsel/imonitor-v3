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
        Schema::table('userlist', function (Blueprint $table) {
            // Adds the column and allows it to be empty (nullable)
            $table->string('current_session_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('userlist', function (Blueprint $table) {
            // Removes the column if you ever need to rollback
            $table->dropColumn('current_session_id');
        });
    }
};
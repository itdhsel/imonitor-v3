<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('userlist', function (Blueprint $table) {
            // Adds a 'role' column. Default is 'user' so existing accounts don't break.
            $table->string('role')->default('user')->after('login_username');
        });
    }

    public function down()
    {
        Schema::table('userlist', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add soft delete column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            // Add indexes for frequently queried columns
            $table->index('email');
            $table->index('created_at');
        });

        // Add soft delete column to posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->softDeletes();
            // Add indexes for frequently queried columns
            $table->index('author_id');
            $table->index('created_at');
            $table->index(['author_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['email']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['author_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['author_id', 'created_at']);
        });
    }
};

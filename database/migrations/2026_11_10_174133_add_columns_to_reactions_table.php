<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('post_id')
                ->after('user_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            $table->string('type', 30)
                ->default('like')
                ->after('post_id');

            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'post_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_id']);

            $table->dropColumn([
                'user_id',
                'post_id',
                'type'
            ]);
        });
    }
};
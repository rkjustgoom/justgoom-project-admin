<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->index(['status', 'deleted_at']);
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->index(['category_id', 'status', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['status', 'deleted_at']);
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropIndex(['category_id', 'status', 'deleted_at']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'user'])->default('user')->after('id');
            $table->string('fname', 100)->nullable()->after('type');
            $table->string('lname', 100)->nullable()->after('fname');
            $table->string('phone', 20)->nullable()->after('password');
            $table->string('country', 100)->nullable()->after('phone');
            $table->string('state', 100)->nullable()->after('country');
            $table->string('city', 100)->nullable()->after('state');
            $table->foreignId('category_id')->nullable()->after('city')->constrained('categories')->restrictOnDelete();
            $table->foreignId('sub_category_id')->nullable()->after('category_id')->constrained('sub_categories')->restrictOnDelete();
            $table->string('profile')->nullable()->after('sub_category_id');
            $table->unsignedTinyInteger('status')->default(1)->after('profile');
            $table->string('referral_code', 20)->nullable()->unique()->after('remember_token');
            $table->softDeletes();
        });

        if (Schema::hasColumn('users', 'name')) {
            DB::table('users')->whereNotNull('name')->update([
                'fname' => DB::raw('name'),
                'lname' => '',
            ]);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->index(['type', 'status', 'deleted_at']);
            $table->index(['category_id', 'sub_category_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['type', 'status', 'deleted_at']);
            $table->dropIndex(['category_id', 'sub_category_id']);
            $table->string('name')->nullable();
        });

        DB::table('users')->update([
            'name' => DB::raw("CONCAT(COALESCE(fname, ''), ' ', COALESCE(lname, ''))"),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignId('sub_category_id');
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn([
                'type',
                'fname',
                'lname',
                'phone',
                'country',
                'state',
                'city',
                'profile',
                'status',
                'referral_code',
            ]);
        });
    }
};

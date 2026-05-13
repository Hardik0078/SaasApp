<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->string('tenant_id')->nullable()->after('causer_id')->index();
        });

        Schema::table('media', function (Blueprint $table) {
            $table->string('tenant_id')->nullable()->after('uuid')->index();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');

        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
};

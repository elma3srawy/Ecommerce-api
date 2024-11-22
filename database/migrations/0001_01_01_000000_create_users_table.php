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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone' , 255)->unique();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone' , 255)->unique();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('identifier')->index();
        //     $table->morphs('tokenable');
        //     $table->string('token', 64)->unique()->index();
        //     $table->timestamp('expires_at');
        //     $table->timestamps();
        // });

        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('token', 64)->unique()->index();
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        // Schema::create('sessions', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->foreignId('user_id')->nullable()->index();
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        //     $table->longText('payload');
        //     $table->integer('last_activity')->index();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('staffs');
        // Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('Verifications');
        // Schema::dropIfExists('sessions');
    }
};

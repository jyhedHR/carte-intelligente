<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->unique();
            $table->ipAddress('ip_address');
            $table->text('user_agent');
            $table->string('device_name')->nullable();
            $table->timestamp('last_activity')->useCurrent();
            $table->timestamp('logged_in_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'last_activity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};

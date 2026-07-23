<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // e.g. "Culturel"
            $table->string('slug')->unique();      // e.g. "culturel"
            $table->string('color', 7)->default('#cdaa80'); // hex color for the dot/marker
            $table->unsignedInteger('order')->default(0);   // display order in legend
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_categories');
    }
};

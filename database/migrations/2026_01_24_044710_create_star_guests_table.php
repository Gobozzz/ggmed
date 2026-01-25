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
        Schema::create('star_guests', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title', 100);
            $table->string('meta_description', 160);
            $table->string('name', 100);
            $table->string('slug', 200)->unique();
            $table->text('points');
            $table->string('url');
            $table->text('content')->nullable();
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('star_guests');
    }
};

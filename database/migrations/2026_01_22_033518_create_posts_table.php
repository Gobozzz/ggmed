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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title', 100)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('title', 100);
            $table->string('description');
            $table->string('slug', 200)->unique();
            $table->string('image');
            $table->text('content');
            $table->unsignedTinyInteger('time_to_read')->default(5);
            $table->foreignId('filial_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('moonshine_users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

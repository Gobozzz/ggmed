<?php

use App\Enums\LevelHipe;
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
        Schema::create('video_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('preview');
            $table->string('video');
            $table->text('images_before')->nullable();
            $table->string('content', 500)->nullable();
            $table->foreignId('filial_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('level_hipe')->default(LevelHipe::LOW);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_reviews');
    }
};

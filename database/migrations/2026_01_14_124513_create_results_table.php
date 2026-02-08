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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->text('images');
            $table->unsignedSmallInteger('count_grafts')->nullable();
            $table->unsignedTinyInteger('count_months')->nullable();
            $table->decimal('panch', 8, 2)->nullable();
            $table->unsignedTinyInteger('level_hipe')->default(LevelHipe::LOW);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};

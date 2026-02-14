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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title', 100)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('title', 100);
            $table->string('slug', 200)->unique();
            $table->string('description');
            $table->text('images');
            $table->decimal('price');
            $table->decimal('old_price')->nullable();
            $table->decimal('price_coin', 10)->nullable();
            $table->boolean('can_buy_only_coin')->default(false);
            $table->string('structure', 100)->nullable();
            $table->string('brand', 50)->nullable();
            $table->boolean('is_have');
            $table->string('article', 50)->nullable()->unique();
            $table->text('content')->nullable();
            $table->unsignedTinyInteger('level_hipe')->default(LevelHipe::LOW);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

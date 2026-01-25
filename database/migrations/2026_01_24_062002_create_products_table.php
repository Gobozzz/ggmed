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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('title');
            $table->string('description', 500);
            $table->text('images');
            $table->decimal('price', 9);
            $table->decimal('old_price', 9)->nullable();
            $table->string('structure')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_have');
            $table->string('article')->nullable()->unique();
            $table->text('content')->nullable();
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

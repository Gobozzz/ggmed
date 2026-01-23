<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title');
            $table->string('meta_description', 500);
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedMediumInteger('price');
            $table->boolean('is_start_price')->default(false);
            $table->string('image');
            $table->text('content');
            $table->string('description', 500);
            $table->foreignId('parent_id')->nullable()->constrained('services')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

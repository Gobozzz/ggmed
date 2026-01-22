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
        Schema::create('filials', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title');
            $table->text('meta_description');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('video')->nullable();
            $table->string('image');
            $table->unsignedSmallInteger('year');
            $table->string('city');
            $table->string('address');
            $table->string('work_time');
            $table->text('map_code');
            $table->foreignId('manager_id')->nullable()->constrained('moonshine_users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filials');
    }
};

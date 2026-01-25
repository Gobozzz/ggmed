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
        Schema::create('filials', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title', 100);
            $table->string('meta_description', 160);
            $table->string('slug', 200)->unique();
            $table->string('phone', 40);
            $table->string('name', 100);
            $table->string('video')->nullable();
            $table->string('image');
            $table->unsignedSmallInteger('year');
            $table->string('city', 50);
            $table->string('address', 70);
            $table->string('work_time', 70);
            $table->string('map_code', 500);
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

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
        Schema::create('exhibit_photo_pivot', function (Blueprint $table) {
            $table->foreignId('exhibit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $table->primary(['exhibit_id', 'photo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibit_photo_pivot');
    }
};

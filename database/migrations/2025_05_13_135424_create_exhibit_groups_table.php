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
        Schema::create('exhibit_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('map_coordinates');
            $table->text('short_description');
            $table->text('description');

            $table->foreignId('museum_id');
            $table->foreignId('next_exhibit_group_id')->nullable();
            $table->foreign('next_exhibit_group_id')
                ->references('id')
                ->on('exhibit_groups')
                ->onDelete('set null');
            $table->foreignId('previous_exhibit_group_id')->nullable();
            $table->foreign('previous_exhibit_group_id')
                ->references('id')
                ->on('exhibit_groups')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibit_groups');
    }
};

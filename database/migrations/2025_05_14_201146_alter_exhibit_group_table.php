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
        Schema::table('exhibit_groups', function (Blueprint $table) {
            $table->unsignedInteger('number')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibit_groups', function (Blueprint $table) {
            $table->removeColumn('number');
            $table->unsignedInteger('number')->unique();
        });
    }
};

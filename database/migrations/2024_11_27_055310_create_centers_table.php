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
        Schema::create('centers', function (Blueprint $table) {
            $table->id();
            $table->integer('workPlaceId')->unsigned()->required();
            $table->integer('officeId')->unsigned()->required();
            $table->mediumInteger('centerNo')->unsigned()->required();
            $table->tinyInteger('centerType')->unsigned()->required();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
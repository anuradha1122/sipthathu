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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->integer('workPlaceId')->unsigned()->required();
            $table->integer('officeId')->unsigned()->required();
            $table->tinyInteger('authorityId')->unsigned()->required();
            $table->tinyInteger('ethnicityId')->unsigned()->required();
            $table->tinyInteger('languageId')->unsigned()->required();
            $table->tinyInteger('classId')->unsigned()->required();
            $table->tinyInteger('densityId')->unsigned()->required();
            $table->tinyInteger('genderId')->unsigned()->required();
            $table->tinyInteger('facilityId')->unsigned()->required();
            $table->tinyInteger('religionId')->unsigned()->required();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};

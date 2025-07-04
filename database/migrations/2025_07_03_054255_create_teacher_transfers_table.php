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
        Schema::create('teacher_transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('refferenceNo')->unsigned()->required();
            $table->integer('userServiceId')->unsigned()->required();
            $table->tinyInteger('typeId')->unsigned()->required();
            $table->tinyInteger('reasonId')->unsigned()->required();
            $table->integer('school1Id')->unsigned()->required();
            $table->integer('school2Id')->unsigned()->nullable();
            $table->integer('school3Id')->unsigned()->nullable();
            $table->integer('school4Id')->unsigned()->nullable();
            $table->integer('school5Id')->unsigned()->nullable();
            $table->tinyInteger('anyschool')->unsigned()->required();
            $table->tinyInteger('gradeId')->unsigned()->nullable();
            $table->integer('alterSchool1Id')->unsigned()->nullable();
            $table->integer('alterSchool2Id')->unsigned()->nullable();
            $table->integer('alterSchool3Id')->unsigned()->nullable();
            $table->integer('alterSchool4Id')->unsigned()->nullable();
            $table->integer('alterSchool5Id')->unsigned()->nullable();
            $table->string('extraCurricular', 1000)->nullable();
            $table->string('mention', 1000)->nullable();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_transfers');
    }
};

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
        Schema::create('student_personal_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('studentId')->unsigned()->required()->unique();
            $table->string('profilePicture', 300)->nullable();
            $table->tinyInteger('raceId')->unsigned()->nullable();
            $table->tinyInteger('religionId')->unsigned()->nullable();
            $table->tinyInteger('genderId')->unsigned()->required();
            $table->tinyInteger('bloodGroupId')->unsigned()->nullable();
            $table->mediumInteger('illnessId')->nullable();
            $table->date('birthDay')->required();
            $table->Integer('birthCertificate')->required();
            $table->mediumInteger('birthDsDivisionId')->nullable();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_personal_infos');
    }
};

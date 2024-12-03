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
        Schema::create('teacher_services', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('userServiceId')->unsigned()->required();
            $table->mediumInteger('appointmentSubjectId')->unsigned()->required();
            $table->mediumInteger('mainSubjectId')->unsigned()->required();
            $table->mediumInteger('appointmentMediumId')->unsigned()->required();
            $table->mediumInteger('appointmentCategoryId')->unsigned()->required();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_services');
    }
};

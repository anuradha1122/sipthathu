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
        Schema::create('school_class_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('schoolId')->unsigned()->required();
            $table->integer('classId')->unsigned()->required();
            $table->integer('teacherId')->unsigned()->nullable();
            $table->tinyInteger('mediumId')->unsigned()->nullable();
            $table->integer('studentCount')->unsigned()->nullable();
            $table->tinyInteger('year')->unsigned()->nullable();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_class_lists');
    }
};

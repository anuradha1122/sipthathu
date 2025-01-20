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
        Schema::create('student_location_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('studentId')->unsigned()->required();
            $table->mediumInteger('educationDivisionId')->unsigned()->nullable();
            $table->mediumInteger('gnDivisionId')->unsigned()->nullable();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_location_infos');
    }
};

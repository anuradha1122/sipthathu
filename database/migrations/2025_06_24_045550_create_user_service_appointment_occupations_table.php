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
        Schema::create('user_service_appointment_occupations', function (Blueprint $table) {
            $table->id();
            $table->integer('userServiceAppointmentId')->unsigned()->required();
            $table->integer('occupationId')->unsigned()->required();
            $table->date('startedDate')->required();
            $table->date('releasedDate')->nullable();
            $table->tinyInteger('current')->default(1)->required();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_service_appointment_occupations');
    }
};

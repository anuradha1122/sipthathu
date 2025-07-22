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
        Schema::create('user_service_appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('userServiceId')->unsigned()->required();
            $table->integer('workPlaceId')->unsigned()->required();
            $table->date('appointedDate')->required();
            $table->date('releasedDate')->nullable();
            $table->tinyInteger('appointmentType')->default(1)->required();
            $table->integer('reason')->nullable();
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
        Schema::dropIfExists('user_service_appointments');
    }
};

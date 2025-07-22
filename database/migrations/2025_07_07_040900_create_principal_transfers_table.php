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
        Schema::create('principal_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refferenceNo')->unsigned()->required();
            $table->unsignedBigInteger('userServiceId')->required();
            $table->string('appointmentLetterNo')->required();
            $table->unsignedTinyInteger('serviceConfirm')->default(0);
            $table->decimal('schoolDistance', 6, 2)->required(); // e.g. 12.25 km
            $table->string('position')->required();
            $table->unsignedTinyInteger('specialChildren')->required();
            $table->unsignedTinyInteger('expectTransfer')->required();
            $table->string('reason')->nullable();

            // Preferred schools
            $table->unsignedBigInteger('school1Id')->nullable();
            $table->decimal('distance1', 6, 2)->nullable();
            $table->unsignedBigInteger('school2Id')->nullable();
            $table->decimal('distance2', 6, 2)->nullable();
            $table->unsignedBigInteger('school3Id')->nullable();
            $table->decimal('distance3', 6, 2)->nullable();
            $table->unsignedBigInteger('school4Id')->nullable();
            $table->decimal('distance4', 6, 2)->nullable();
            $table->unsignedBigInteger('school5Id')->nullable();
            $table->decimal('distance5', 6, 2)->nullable();

            $table->unsignedTinyInteger('anySchool')->required();
            $table->string('mention')->nullable();     // additional notes
            $table->unsignedTinyInteger('active')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('principal_transfers');
    }
};

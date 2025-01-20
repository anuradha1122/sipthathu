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
        Schema::create('student_contact_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('studentId')->unsigned()->required()->unique();
            $table->string('addressLine1', 80)->required();
            $table->string('addressLine2', 80)->required();
            $table->string('addressLine3', 80)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->string('guardianName', 80)->nullable();
            $table->tinyInteger('guardianRelationshipId')->required();
            $table->string('guardianMobile', 10)->nullable();
            $table->string('guardianEmail', 80)->nullable();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_contact_infos');
    }
};

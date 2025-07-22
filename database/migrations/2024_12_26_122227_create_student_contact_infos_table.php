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
            $table->string('addressLine1', 100)->required();
            $table->string('addressLine2', 100)->required();
            $table->string('addressLine3', 100)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->string('motherName', 200)->nullable();
            $table->string('motherNic', 12)->nullable();
            $table->string('motherMobile', 10)->nullable();
            $table->string('motherEmail', 80)->nullable();
            $table->string('fatherName', 200)->nullable();
            $table->string('fatherNic', 12)->nullable();
            $table->string('fatherMobile', 10)->nullable();
            $table->string('fatherEmail', 80)->nullable();
            $table->string('guardianName', 200)->nullable();
            $table->string('guardianNic', 12)->nullable();
            $table->tinyInteger('guardianRelationshipId')->nullable();
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

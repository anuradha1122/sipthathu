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
        Schema::create('education_qualification_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('userId')->unsigned()->required();
            $table->mediumInteger('educationQualificationId')->unsigned()->nullable();
            $table->date('effectiveDate')->required();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_qualification_infos');
    }
};

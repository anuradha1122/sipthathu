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
        Schema::create('family_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('userId')->unsigned()->required();
            $table->tinyInteger('memberType')->unsigned()->nullable();
            $table->string('nic', 12)->nullable();
            $table->string('name', 200)->required();
            $table->mediumInteger('school')->unsigned()->nullable();
            $table->string('profession', 200)->nullable();
            $table->tinyInteger('active')->default(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_infos');
    }
};

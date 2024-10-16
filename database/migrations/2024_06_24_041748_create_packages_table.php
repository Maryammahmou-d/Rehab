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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->json('makkah');
            $table->json('shifting_makkah');
            $table->json('madinah');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('org_id')->index();
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->string('package_nickname')->nullable();
            $table->string('package_name_arabic')->nullable();
            $table->string('package_name_english')->nullable();
            $table->string('package_type')->nullable();
            $table->string('camp')->nullable();
            $table->string('country')->nullable();
            $table->text('description_arabic')->nullable();
            $table->text('description_english')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

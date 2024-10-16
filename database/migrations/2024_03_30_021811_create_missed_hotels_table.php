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
        Schema::create('missed_hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name');
            $table->string('hotel_location')->nullable();
            $table->string('hotel_company')->nullable();
            $table->string('hotel_city');
            $table->string('hotel_category')->nullable();
            $table->string('hotel_zone')->nullable();
            $table->string('hotel_phone')->nullable();
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->timestamps();
        });
        DB::table('missed_hotels')->insert([
            'hotel_name' => 'Default Hotel',
            'hotel_location' => 'Default Location',
            'hotel_company' => 'Default Company',
            'hotel_city' => 'Default City',
            'hotel_category' => 'Default Category',
            'hotel_zone' => 'Default Zone',
            'hotel_phone' => '1234567890',
            'org_id' => 1, // Assuming organization with ID 1 exists
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missed_hotels');
    }
};

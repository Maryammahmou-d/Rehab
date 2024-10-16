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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name');
             $table->string('hotel_company')->nullable();
            $table->string('hotel_city');
            $table->string('hotel_category')->nullable();
            $table->string('hotel_zone')->nullable();
             $table->timestamps();

        });

        DB::table('hotels')->insert([
            'hotel_name' => 'Default Hotel',
             'hotel_company' => 'Default Company',
            'hotel_city' => 'Default City',
            'hotel_category' => 'Default Category',
            'hotel_zone' => 'Default Zone',
             'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};

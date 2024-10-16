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
        Schema::create('hotel_contracts_entries', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')->references('id')->on('hotel_contracts_buyers');
            $table->integer('rooms_needed')->nullable();
            $table->text('rooms_type')->nullable();
            $table->integer('pilgrims')->nullable();
            $table->text('group_name')->nullable();
            $table->text('nusuk_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('hotel_cost')->nullable();
            $table->float('room_cost')->nullable();
            $table->float('meal_cost')->nullable();
            $table->float('days_meals')->nullable();
            $table->float('food_cost')->nullable();
            $table->float('hotel_cost_1person')->nullable();
            $table->float('food_cost_1person')->nullable();
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->timestamps();
        });
        DB::table('hotel_contracts_entries')->insert([
            'title' => 'Default Contract Entry',
            'hotel_id' => 1, // Assuming hotel with ID 1 exists
            'rooms_needed' => 10,
            'rooms_type' => 'Double',
            'pilgrims' => 20,
            'group_name' => 'Default Group',
            'nusuk_id' => 'NUSUK123',
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'hotel_cost' => 1000.00,
            'room_cost' => 100.00,
            'meal_cost' => 50.00,
            'days_meals' => 7,
            'food_cost' => 350.00,
            'hotel_cost_1person' => 50.00,
            'food_cost_1person' => 25.00,
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
        Schema::dropIfExists('hotel_contracts_entries');
    }
};

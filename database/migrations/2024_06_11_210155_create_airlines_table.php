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
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('pnr_no');
            $table->integer('no_of_tickets');
            $table->date('outbound_departure_date')->nullable();
            $table->date('outbound_arrival_date')->nullable();
            $table->text('outbound_departure')->nullable();
            $table->text('outbound_arrival')->nullable();
            $table->text('return_departure')->nullable();
            $table->text('return_arrival')->nullable();
            $table->string('airline_provider');
            $table->decimal('pnr_total_ticket_price', 10, 2);
            $table->decimal('one_person_price_for_ticket', 10, 2);
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');

            $table->timestamps();
        });

        DB::table('airlines')->insert([
            'pnr_no' => 'DEFAULTPNR123',
            'no_of_tickets' => 1,
            'outbound_departure_date' => now(),
            'outbound_arrival_date' => now()->addDay(),
            'outbound_departure' => 'Default Departure Location',
            'outbound_arrival' => 'Default Arrival Location',
            'return_departure' => 'Default Return Departure Location',
            'return_arrival' => 'Default Return Arrival Location',
            'airline_provider' => 'Default Airline Provider',
            'pnr_total_ticket_price' => 100.00,
            'one_person_price_for_ticket' => 100.00,
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
        Schema::dropIfExists('airlines');
    }
};

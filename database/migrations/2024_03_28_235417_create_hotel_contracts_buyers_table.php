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
        Schema::create('hotel_contracts_buyers', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('file_path');
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->timestamps();
        });
        DB::table('hotel_contracts_buyers')->insert([
            'title' => 'Example Contract',
            'file_path' => '/path/to/contract.pdf',
            'org_id' => 1, // Assuming organization with ID 1 exists
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_contracts_buyers');
    }
};

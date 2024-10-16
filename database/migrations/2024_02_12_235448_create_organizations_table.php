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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('domain');
            $table->string('email');
            $table->string('phone');
            $table->string('logo')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
            // Insert default data into the organizations table
    DB::table('organizations')->insert([
        'title' => 'Example Organization',
        'domain' => 'example.org',
        'email' => 'contact@example.org',
         'phone' => '1234567890',
        'logo' => null,
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};

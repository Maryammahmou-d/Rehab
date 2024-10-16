<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->integer('org_id');
            $table->string('status')->default(0);
            $table->boolean('email_verified')->default(0);
            $table->boolean('sms_verified')->default(0);
            $table->string('password');
            $table->string('phone')->nullable();
            $table->rememberToken();
             $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
        DB::table('users')->insert([
            'fname' => 'Default',
            'lname' => 'User',
            'email' => 'test@gmail.com',
            'org_id' => 1,
            'status' => 'active',
            'email_verified' => true,
            'sms_verified' => true,
            'password' => bcrypt('123'),
            'phone' => null,
             'profile_photo_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

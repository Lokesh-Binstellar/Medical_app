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
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id();
            $table->string('lab_name');
            $table->string('owner_name');
            $table->unsignedBigInteger('user_id');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->longText('image')->nullable();
            $table->string('username');
            $table->string('password');
            $table->string('license');
            $table->string('gstno');
            $table->boolean('nabl_iso_certified');
            $table->boolean('pickup');
            $table->json('test')->nullable();
            $table->boolean('status')->default(1); // 👈 Active/Inactive
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratories');
    }
};

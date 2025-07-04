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
        Schema::create('lab_slot_bookings', function (Blueprint $table) {
               $table->id();

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('lab_slot_id');

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');

            $table->timestamps();

            // Foreign keys
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('lab_slot_id')->references('id')->on('lab_slots')->onDelete('cascade');

        });
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_slot_bookings');
    }
};

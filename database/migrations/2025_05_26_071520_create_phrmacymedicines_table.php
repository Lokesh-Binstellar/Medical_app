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
        Schema::create('phrmacymedicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('phrmacy_id');
            $table->json('medicine');       
            $table->json('substitute_medicines')->nullable();    
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('mrp_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('phrmacy_id')->references('user_id')->on('pharmacies')->onDelete('cascade');
             $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade'); // ✅ Foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phrmacymedicines');
    }
};

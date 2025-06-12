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
        Schema::create('quote_accept_logs', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('customer_id');
    $table->unsignedBigInteger('pharmacy_id');
    $table->timestamp('requested_at')->nullable();
    $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_accept_logs');
    }
};

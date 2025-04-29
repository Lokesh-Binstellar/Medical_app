<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('added_medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('mrp', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->enum('available', ['yes', 'no']);
            $table->enum('has_salt', ['yes', 'no']);
            $table->timestamps();
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('added_medicines');
    }
};

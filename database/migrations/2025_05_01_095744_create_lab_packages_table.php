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
        Schema::create('lab_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lab_id');
            $table->unsignedBigInteger('package_category_id');
            $table->string('package_name');
            $table->decimal('home_price', 8, 2);
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();   
            // Foreign keys
            $table->foreign('lab_id')->references('id')->on('laboratories')->onDelete('cascade');
            $table->foreign('package_category_id')->references('id')->on('package_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_packages');
    }
};

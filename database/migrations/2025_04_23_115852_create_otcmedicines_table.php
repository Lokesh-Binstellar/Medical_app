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
        Schema::create('otcmedicines', function (Blueprint $table) {
            $table->id();
            $table->string('otc_id')->unique();
            $table->string('name'); // name column
            $table->string('breadcrumbs')->nullable(); // breadcrumbs column
            $table->string('manufacturers')->nullable(); // manufacturers column
            $table->string('type')->nullable(); // type column
            $table->string('packaging')->nullable(); // packaging column
            $table->string('package')->nullable(); // Package column
            $table->string('qty')->nullable(); // Qty column
            $table->string('product_form')->nullable(); // Product Form column         
            $table->text('product_highlights')->nullable(); // product_highlights column
            $table->text('information')->nullable(); // Information column
            $table->text('key_ingredients')->nullable(); // Key Ingredients column
            $table->text('key_benefits')->nullable(); // Key Benefits column
            $table->text('directions_for_use')->nullable(); // Directions for Use column
            $table->text('safety_information')->nullable(); // Safety Information column
            $table->string('manufacturer_address')->nullable(); // MANUFACTURER_ADDRESS column
            $table->string('country_of_origin')->nullable(); // country_of_origin column
            $table->text('manufacturer_details')->nullable(); // Manufacturer details column
            $table->text('marketer_details')->nullable(); // Marketer details column
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otcmedicines');
    }
};

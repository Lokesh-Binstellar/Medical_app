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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->unique(); // Like DRS000001
            $table->string('product_name');
            $table->string('marketer')->nullable();
            $table->text('salt_composition')->nullable();
            $table->string('medicine_type')->nullable();
            $table->text('introduction')->nullable();
            $table->text('benefits')->nullable();
            $table->text('description')->nullable();
            $table->text('how_to_use')->nullable();
            $table->text('safety_advise')->nullable();
            $table->text('if_miss')->nullable();
            $table->text('packaging_detail')->nullable();
            $table->string('package')->nullable();
            $table->string('qty')->nullable();
            $table->string('product_form')->nullable();
            $table->string('prescription_required')->nullable();
            $table->text('fact_box')->nullable();
            $table->string('primary_use')->nullable();
            $table->text('storage')->nullable();
            $table->text('use_of')->nullable();
            $table->text('common_side_effect')->nullable();
            $table->text('alcohol_interaction')->nullable();
            $table->text('pregnancy_interaction')->nullable();
            $table->text('lactation_interaction')->nullable();
            $table->text('driving_interaction')->nullable();
            $table->text('kidney_interaction')->nullable();
            $table->text('liver_interaction')->nullable();
            $table->text('manufacturer_address')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->text('q_a')->nullable();
            $table->text('how_it_works')->nullable();
            $table->text('interaction')->nullable();
            $table->text('manufacturer_details')->nullable();
            $table->text('marketer_details')->nullable();    
            $table->string('image_url')->nullable();    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};

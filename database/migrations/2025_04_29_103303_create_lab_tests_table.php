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
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('contains')->nullable(); 
            $table->string('gender')->nullable();
            $table->string('reports_in')->nullable(); 
            $table->string('sample_required')->default(true);
            $table->text('preparation')->nullable();
            $table->text('how_does_it_work')->nullable();
            $table->text('sub_reports')->nullable(); 
            $table->text('sub_report_details')->nullable(); 
            $table->text('faq')->nullable();
            $table->text('references')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};

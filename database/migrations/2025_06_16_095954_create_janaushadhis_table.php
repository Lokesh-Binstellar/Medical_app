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
        Schema::create('janaushadhis', function (Blueprint $table) {
            $table->id();
            $table->integer('drug_code')->unique();
            $table->string('generic_name');
            $table->string('unit_size');
            $table->decimal('mrp', 8, 2);
            $table->string('group_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('janaushadhis');
    }
};

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
        Schema::table('request_quotes', function (Blueprint $table) {
            // Add prescription_id (assuming it's a foreign key)
            $table->json('prescription_id')->nullable()->after('id');
            $table->json('products_details')->nullable()->after('prescription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_quotes', function (Blueprint $table) {
            $table->dropColumn(['prescription_id', 'products_details']);
        });
    }
};

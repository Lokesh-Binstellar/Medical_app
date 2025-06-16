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
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->decimal('free_delivery_charge', 8, 2)->default(0)->after('pharmacy_name');
        });
        Schema::table('laboratories', function (Blueprint $table) {
            $table->decimal('free_delivery_charge', 8, 2)->default(0)->after('lab_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pharmacies', 'free_delivery_charge')) {
            Schema::table('pharmacies', function (Blueprint $table) {
                $table->dropColumn('free_delivery_charge');
            });
        }
         if (Schema::hasColumn('laboratories', 'free_delivery_charge')) {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropColumn('free_delivery_charge');
        });
    }
    }
};

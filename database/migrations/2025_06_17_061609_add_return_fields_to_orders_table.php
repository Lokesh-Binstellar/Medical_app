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
        Schema::table('orders', function (Blueprint $table) {
             $table->json('return_accepted_items')->nullable()->after('returned_items');
        $table->decimal('total_return_amount', 10, 2)->default(0)->after('return_accepted_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('return_accepted_items');
            $table->dropColumn('total_return_amount');
        });
        });
    }
};

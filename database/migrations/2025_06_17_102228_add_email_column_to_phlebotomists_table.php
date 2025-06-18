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
        Schema::table('phlebotomists', function (Blueprint $table) {
            
            $table->renameColumn('name','phlebotomists_name');
            $table->string('email');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->text('address')->nullable();
            $table->string('username')->nullable();
            $table->string('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phlebotomists', function (Blueprint $table) {
            $table->dropColumn('phlebotomists_name');
            $table->dropColumn('email');
$table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('pincode');
            $table->dropColumn('address');
            $table->dropColumn('username');
            $table->dropColumn('password');

        });
    }
};

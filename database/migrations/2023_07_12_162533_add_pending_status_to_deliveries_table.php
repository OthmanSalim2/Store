<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->enum('status', ['pending', 'delivered', 'in-progress'])
                ->default('pending')
                // I here say to laravel I modify the column and not adding
                ->change();

            // other way

            // DB::statement("ALTER TABLE `deliveries`
            //     CHANGE COLUMN `status` `status` ENUM('pending', 'delivered', 'in-progress') NOT NULL DEFAULT 'pending'
            //     ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->enum('status', ['delivered', 'in-progress'])
                ->default('in-progress')
                // here not the purpose deleting but returned to the original mean modify the column
                ->change();


            //other way
            //       old name status   new name status
            // DB::statement("ALTER TABLE `deliveries`
            //     CHANGE COLUMN `status` `status` ENUM('delivered', 'in-progress') NOT NULL DEFAULT 'in-progress'
            //     ");
        });
    }
};

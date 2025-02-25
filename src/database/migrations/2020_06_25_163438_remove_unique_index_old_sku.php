<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueIndexOldSku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->dropIndex('old_stock_keeping_unit_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->unique(['old_stock_keeping_unit'], 'old_stock_keeping_unit_index_unique');
        });
    }
}

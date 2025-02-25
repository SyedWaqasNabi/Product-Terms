<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTradeItemOffersTableOldStockKeepingUnitMakeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->string('old_stock_keeping_unit')->nullable()->change();
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
            $table->string('old_stock_keeping_unit')->nullable(false)->change();
        });
    }
}

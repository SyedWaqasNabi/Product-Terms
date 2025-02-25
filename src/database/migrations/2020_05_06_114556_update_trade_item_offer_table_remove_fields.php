<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTradeItemOfferTableRemoveFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->dropColumn('price_type');
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->dropColumn('sales_unit_type');
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
            $table->string('price_type')->nullable();
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->string('sales_unit_type')->nullable();
        });
    }
}

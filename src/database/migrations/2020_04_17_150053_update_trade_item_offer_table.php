<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTradeItemOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->string('trade_item_id')->nullable();
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->unique(['trade_item_id', 'supplier_id', 'customer_group_id'], 'trade_item_id_index_unique');
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->dropColumn('global_trade_item_number');
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->renameColumn('validTo', 'valid_to');
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->renameColumn('validFrom', 'valid_from');
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
            $table->dropIndex('trade_item_id_index_unique');
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->dropColumn('trade_item_id');
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->string('global_trade_item_number')->nullable();
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->renameColumn('valid_to', 'validTo');
        });

        Schema::table('trade_item_offer', function (Blueprint $table) {
            $table->renameColumn('valid_from', 'validFrom');
        });
    }
}

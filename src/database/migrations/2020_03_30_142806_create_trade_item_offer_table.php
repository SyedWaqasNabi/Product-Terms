<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateTradeItemOffer
 */
class CreateTradeItemOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_item_offer', function (Blueprint $table) {
            $table->id();
            $table->string('internal_name');
            $table->string('supplier_id');
            $table->string('supplier_trade_item_number');
            $table->string('global_trade_item_number');
            $table->string('customer_group_id');
            $table->decimal('net_price', 14, 8);
            $table->string('price_type');
            $table->string('currency', 3)->nullable();
            $table->decimal('sales_unit', 14, 8);
            $table->string('sales_unit_type');
            $table->string('stock_keeping_unit');
            $table->string('old_stock_keeping_unit');
            $table->unsignedInteger('maximum_delivery_time')->nullable();
            $table->unsignedInteger('minimum_delivery_time')->nullable();
            $table->string('delivery_time_unit');
            $table->unsignedInteger('minimum_order_quantity')->nullable();
            $table->unsignedInteger('maximum_order_quantity')->nullable();
            $table->timestamp('validFrom')->nullable();
            $table->timestamp('validTo')->nullable();
            $table->tinyInteger('import_status')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_warehouse_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_item_offer');
    }
}

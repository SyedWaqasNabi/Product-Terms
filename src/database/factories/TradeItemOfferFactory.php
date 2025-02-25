<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TradeItemOffer;
use Faker\Generator as Faker;
use App\Generators\TradeItemOfferSKU;

$factory->define(TradeItemOffer::class, function (Faker $faker) {
    $tradeItemId = $faker->randomNumber(4);
    $supplierId = $faker->randomNumber(4);
    $customerGroupId = $faker->randomNumber(4);

    return [
        'trade_item_id' => $tradeItemId,
        'internal_name' => $faker->sentence(5),
        'supplier_id' => $supplierId,
        'supplier_trade_item_number' => $faker->text(15),
        'customer_group_id' => $customerGroupId,
        'net_price' => $faker->randomFloat(8, 0, 1000),
        'currency' => $faker->randomElement(['EUR', 'USD', 'GBP', 'RMB', 'UAH']),
        'sales_unit' => $faker->randomFloat(8, 0, 10),
        'stock_keeping_unit' => TradeItemOfferSKU::generateSku($tradeItemId, $supplierId, $customerGroupId),
        'old_stock_keeping_unit' => TradeItemOfferSKU::generateOldSku($tradeItemId, $supplierId),
        'maximum_delivery_time' => $faker->numberBetween(3, 10),
        'minimum_delivery_time' => $faker->numberBetween(1, 3),
        'delivery_time_unit' => $faker->randomElement(['day', 'hour', 'week']),
        'maximum_order_quantity' => $faker->numberBetween(5, 1000),
        'minimum_order_quantity' => $faker->numberBetween(1, 5),
        'valid_from' => $faker->dateTime('-5 days'),
        'valid_to' => $faker->dateTime('+5 days'),
        'import_status' => $faker->randomElement(['pending', 'processed', 'completed']),
        'is_active' => $faker->boolean,
        'is_warehouse_item' => $faker->boolean
    ];
});

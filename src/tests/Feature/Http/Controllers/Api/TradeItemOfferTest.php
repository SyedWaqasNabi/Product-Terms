<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Jobs\TradeItemOfferDelete;
use App\Jobs\TradeItemOfferStore;
use App\Jobs\TradeItemOfferUpdate;
use App\Models\TradeItemOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class TradeItemOfferTest
 * @package Tests\Feature\Http\Controllers\Api
 */
class TradeItemOfferTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Non Authenticated user cannot access the api
     * @test
     */
    public function userAuthenticationCheck()
    {
        $index = $this->json('GET', self::TRADE_ITEM_OFFER_REST);
        $index->assertStatus(self::STATUS_CODE_NOT_AUTHENTICATED);
    }

    /**
     * Check Api Status
     * @test
     */
    public function apiStatus()
    {
        $response = $this->json('GET', self::TRADE_ITEM_OFFER_REST, [], $this->authenticationHeaders());
        $response->assertStatus(self::STATUS_CODE_SUCCESS);
    }

    /**
     * Check structure of api return
     * @test
     */
    public function verifyReturnDataStructure()
    {
        $tradeItemOfferData = \factory(TradeItemOffer::class)->create();
        $response = $this->json('GET', self::TRADE_ITEM_OFFER_REST, [], $this->authenticationHeaders());
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'type',
                    'attributes' => [
                        'trade_item_id',
                        'internal_name',
                        'supplier_id',
                        'supplier_trade_item_number',
                        'customer_group_id',
                        'net_price',
                        'currency',
                        'sales_unit',
                        'stock_keeping_unit',
                        'old_stock_keeping_unit',
                        'maximum_delivery_time',
                        'minimum_delivery_time',
                        'delivery_time_unit',
                        'minimum_order_quantity',
                        'maximum_order_quantity',
                        'valid_from',
                        'valid_to',
                        'import_status',
                        'is_active',
                        'is_warehouse_item',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => [
                'current_page', 'last_page', 'from', 'to',
                'path', 'per_page', 'total'
            ]
        ]);
        // for testing data type
        $responseArray = json_decode($response->getContent());
        foreach ($responseArray->data as $data) {
            $tradeItemId = $data->attributes->trade_item_id;
            $internalName = $data->attributes->internal_name;
            $supplierId = $data->attributes->supplier_id;
            $supplierTradeItemNumber = $data->attributes->supplier_trade_item_number;
            $customerGroupId = $data->attributes->customer_group_id;
            $netPrice = $data->attributes->net_price;
            $currency = $data->attributes->currency;
            $salesUnit = $data->attributes->sales_unit;
            $stockKeepingUnit = $data->attributes->stock_keeping_unit;
            $oldStockKeepingUnit = $data->attributes->stock_keeping_unit;
            $maximumDeliveryTime = $data->attributes->maximum_delivery_time;
            $minimumDeliveryTime = $data->attributes->minimum_delivery_time;
            $deliveryTimeUnit = $data->attributes->delivery_time_unit;
            $minimumOrderQuantity = $data->attributes->minimum_order_quantity;
            $maximumOrderQuantity = $data->attributes->maximum_order_quantity;
            $validFrom = $data->attributes->valid_from;
            $validTo = $data->attributes->valid_to;
            $importStatus = $data->attributes->import_status;
            $isActive = $data->attributes->is_active;
            $isWareHouse = $data->attributes->is_warehouse_item;

            $this->assertIsInt($tradeItemId);
            $this->assertIsString($internalName);
            $this->assertIsInt($supplierId);
            $this->assertIsString($supplierTradeItemNumber);
            $this->assertIsInt($customerGroupId);
            $this->assertIsFloat($netPrice);
            $this->assertIsString($currency);
            $this->assertIsFloat($salesUnit);
            $this->assertIsString($stockKeepingUnit);
            $this->assertIsString($oldStockKeepingUnit);
            $this->assertIsInt($maximumDeliveryTime);
            $this->assertIsInt($minimumDeliveryTime);
            $this->assertIsString($deliveryTimeUnit);
            $this->assertIsString($minimumOrderQuantity);
            $this->assertIsString($maximumOrderQuantity);
            $this->assertIsString($validFrom);
            $this->assertIsString($validTo);
            $this->assertIsString($importStatus);
            $this->assertIsBool($isActive);
            $this->assertIsBool($isWareHouse);
        }
    }

    /**
     * Check count of items
     * @test
     */
    public function checkItemsCount()
    {
        $response = $this->json(
            'GET',
            self::TRADE_ITEM_OFFER_REST,
            [],
            $this->authenticationHeaders()
        );
        $responseArray = json_decode($response->getContent());
        $response->assertStatus(self::STATUS_CODE_SUCCESS);
        if (property_exists($responseArray, "meta")) {
            $this->assertNotNull($responseArray->meta->total);
            $this->assertGreaterThanOrEqual(0, $responseArray->meta->total);
        }
    }

    /**
     * Pagination Test
     * @test
     */
    public function checkPagination()
    {
        // get the current pagination limit from the .env
        $currentPaginationLimit = env('PAGINATION_LIMIT', 15);
        $response = $this->json(
            'GET',
            self::TRADE_ITEM_OFFER_REST,
            [],
            $this->authenticationHeaders()
        );
        $responseArray = json_decode($response->getContent());
        $response->assertStatus(self::STATUS_CODE_SUCCESS);
        if (property_exists($responseArray, "meta")) {
            $this->assertEquals($currentPaginationLimit, $responseArray->meta->per_page);
        }
    }

    /**
     * Filters testing
     * @test
     */
    public function checkApplyFilters()
    {
        $tradeItemOfferData = \factory(TradeItemOffer::class)->create();
        $tradeItemOfferArray = $tradeItemOfferData->toArray();
        $isActive = $tradeItemOfferArray['is_active'];
        $supplierId = $tradeItemOfferArray['supplier_id'];
        $isWareHouseItem = $tradeItemOfferArray['is_warehouse_item'];

        $filter = http_build_query([
            'filter' => [
                'is_active' => $isActive,
                'supplier_id' => $supplierId,
                'is_warehouse_item' => $isWareHouseItem
            ],
        ]);
        $response = $this->json(
            'GET',
            self::TRADE_ITEM_OFFER_REST . '?' . $filter,
            [],
            $this->authenticationHeaders()
        );
        $response->assertStatus(self::STATUS_CODE_SUCCESS);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $responseArray);
    }

    /**
     * Creat record test
     * @test
     */
    public function checkCreateRecord()
    {
        Queue::fake();

        $tradeItemOfferData = \factory(TradeItemOffer::class)->create();
        $tradeItemOfferArray = $tradeItemOfferData->toArray();

        $this->json(
            'POST',
            self::TRADE_ITEM_OFFER_REST,
            $tradeItemOfferArray,
            $this->authenticationHeaders()
        );

        Queue::assertPushed(TradeItemOfferStore::class, function ($job) use ($tradeItemOfferArray) {
            return $job->getTradeItemId() == $tradeItemOfferArray['trade_item_id']
                && $job->getSupplierId() == $tradeItemOfferArray['supplier_id']
                && $job->getCustomerGroupId() == $tradeItemOfferArray['customer_group_id'];
        });
        Queue::assertPushedOn(TradeItemOfferStore::IMPORT_QUEUE_NAME, TradeItemOfferStore::class);
    }

    /**
     * Update record test
     */
    public function checkUpdateRecord()
    {
        Queue::fake();

        $tradeItemOfferData = \factory(TradeItemOffer::class)->create();
        $tradeItemOfferArray = $tradeItemOfferData->toArray();
        $id = $tradeItemOfferArray['id'];

        $this->json(
            'PUT',
            self::TRADE_ITEM_OFFER_REST . '/' . $id,
            $tradeItemOfferArray,
            $this->authenticationHeaders()
        );

        Queue::assertPushed(TradeItemOfferStore::class, function ($job) use ($tradeItemOfferArray) {
            return $job->getOfferId() === $tradeItemOfferArray['id']
                && $job->getTradeItemId() === $tradeItemOfferArray['trade_item_id']
                && $job->getSupplierId() === $tradeItemOfferArray['supplier_id']
                && $job->getCustomerGroupId() === $tradeItemOfferArray['customer_group_id'];
        });
        Queue::assertPushedOn(TradeItemOfferUpdate::IMPORT_QUEUE_NAME, TradeItemOfferStore::class);
    }

    /**
     * Delete record test
     * @test
     */
    public function checkDeleteRecord()
    {
        Queue::fake();

        $tradeItemOfferData = \factory(TradeItemOffer::class)->create();
        $tradeItemOfferArray = $tradeItemOfferData->toArray();
        $id = $tradeItemOfferArray['id'];

        $this->json(
            'DELETE',
            self::TRADE_ITEM_OFFER_REST . '/' . $id,
            [],
            $this->authenticationHeaders()
        );

        Queue::assertPushed(TradeItemOfferDelete::class, function ($job) use ($tradeItemOfferArray) {
            return $job->getOfferId() === $tradeItemOfferArray['id'];
        });
        Queue::assertPushedOn(TradeItemOfferDelete::DELETE_QUEUE_NAME, TradeItemOfferDelete::class);
    }
}

<?php

namespace App\Repositories;

use App\Generators\TradeItemOfferSKU;
use App\Helpers\DateHelper;
use App\Models\TradeItemOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class TradeItemOfferRepository
 * @package App\Repositories
 */
class TradeItemOfferRepository
{
    const HASH_MAPPING = 'trade-item-offer.hash-mapping';

    /**
     * @var string|null $tradeItemOfferMD5
     */
    private ?string $tradeItemOfferMD5 = null;

    /**
     * @var TradeItemOffer $tradeItemOffer
     */
    private TradeItemOffer $tradeItemOffer;

    /**
     * TradeItemOfferRepository constructor.
     * @param TradeItemOffer $tradeItemOffer
     */
    public function __construct(TradeItemOffer $tradeItemOffer)
    {
        $this->tradeItemOffer = $tradeItemOffer;
    }

    /**
     * @param string $id
     * @return TradeItemOffer
     */
    public function find(string $id): TradeItemOffer
    {
        $query = clone $this->query();

        return $query->findOrFail($id);
    }

    /**
     * @param $data
     */
    public function create($data)
    {
        $tradeItemOffer = clone $this->tradeItemOffer;
        $this->change($tradeItemOffer, $data);
    }

    /**
     * @param array $data
     */
    public function update(array $data)
    {
        $data = $this->prepareData($data);
        if (!$this->validateUpdating($data)) {
            Log::warning(sprintf(
                'Comparing hash values while updating trade item offer. Hash is equal. ' .
                'No need to update trade item with trade_item_id = %s, supplier_id = %s, customer_group_id = %s.',
                $data['trade_item_id'],
                $data['supplier_id'],
                $data['customer_group_id']
            ));

            return;
        }

        $this->change($this->tradeItemOffer, $data);
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        $this->tradeItemOffer->delete();
    }

    /**
     * @return Builder[]|Collection
     */
    public function getTradeItemOfferFamilies()
    {
        $query = clone $this->query();

        return $query
            ->where('is_active', true)
            ->orderBy('trade_item_id')
            ->whereIn('trade_item_id', function ($q) {
                $q->select('trade_item_id')
                    ->from('trade_item_offer')
                    ->groupBy('trade_item_id')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->get()
            ->groupBy(['trade_item_id']);
    }

    /**
     * @param int $tradeItemId
     * @return Builder[]|Collection
     */
    public function getTradeItemOfferFamilyById(int $tradeItemId)
    {
        $query = clone $this->query();

        return $query
            ->where('is_active', true)
            ->where('trade_item_id', '=', $tradeItemId)
            ->get();
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareData(array $data): array
    {
        if (isset($data['is_active'])) {
            $data['is_active'] = (int)$data['is_active'];
        }
        if (isset($data['is_warehouse_item'])) {
            $data['is_warehouse_item'] = (int)$data['is_warehouse_item'];
        }

        return $data;
    }

    /**
     * @param TradeItemOffer $tradeItemOffer
     * @param array $data
     */
    private function change(TradeItemOffer $tradeItemOffer, array $data): void
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $tradeItemOffer->getFillable())) {
                $tradeItemOffer->$key = $value;
            }
        }

        $tradeItemOffer->stock_keeping_unit = $tradeItemOffer->stock_keeping_unit
            ?? TradeItemOfferSKU::generateSku(
                $data['trade_item_id'],
                $data['supplier_id'],
                $data['customer_group_id']
            );

        $tradeItemOffer->customer_group_id = $tradeItemOffer->customer_group_id
            ?? $data['customer_group_id'];

        $tradeItemOffer->supplier_id = $tradeItemOffer->supplier_id
            ?? $data['supplier_id'];

        if (isset($data['old_stock_keeping_unit']) && $data['old_stock_keeping_unit']) {
            $tradeItemOffer->old_stock_keeping_unit = $data['old_stock_keeping_unit'];
        }

        $this->setDateField('valid_from', $tradeItemOffer, $data);
        $this->setDateField('valid_to', $tradeItemOffer, $data);

        $tradeItemOffer->hash = $this->tradeItemOfferMD5 ?? $this->generateTradeItemOfferMD5($data);

        if (!isset($data['import_status'])) {
            $tradeItemOffer->import_status = $tradeItemOffer->is_active
                ? TradeItemOffer::STATUS_PENDING
                : TradeItemOffer::STATUS_NOT_IMPORTED;
        }

        $tradeItemOffer->save();
    }

    /**
     * @param array $data
     * @return bool
     */
    private function validateUpdating(array $data): bool
    {
        if (!$this->tradeItemOffer->stock_keeping_unit || isset($data['import_status'])) {
            return true;
        }

        $this->tradeItemOfferMD5 = $this->generateTradeItemOfferMD5($data);

        return $this->tradeItemOffer->hash !== $this->tradeItemOfferMD5;
    }

    /**
     * @param array $data
     * @return string
     */
    private function generateTradeItemOfferMD5(array $data): string
    {
        $tradeItemOfferHashData = [];
        foreach (config(self::HASH_MAPPING) as $hashRow) {
            if (isset($data[$hashRow])) {
                $tradeItemOfferHashData[$hashRow] = $data[$hashRow];
            }
        }

        return md5(json_encode($tradeItemOfferHashData));
    }

    /**
     * @return Builder|Model
     */
    private function query(): Builder
    {
        return $this->tradeItemOffer->newModelQuery();
    }

    /**
     * @param $key
     * @param $tradeItemOffer
     * @param $data
     */
    private function setDateField($key, $tradeItemOffer, $data)
    {
        if (isset($data[$key])) {
            $isValid = DateHelper::validateDate($data, $key);
            if (!$isValid) {
                $tradeItemOffer->$key = null;
            }
        }
    }
}

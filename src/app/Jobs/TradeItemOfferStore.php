<?php

namespace App\Jobs;

use App\Models\TradeItemOffer;
use App\Repositories\TradeItemOfferRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TradeItemOfferUpdate
 * @package App\Jobs
 */
class TradeItemOfferStore extends AbstractJob implements ShouldQueue
{
    const IMPORT_QUEUE_NAME = 'IMPORT_TIO_PTS';

    protected array $tradeItemOfferData;

    /**
     * Create a new job instance.
     * TradeItemOfferUpdate constructor.
     * @param array $tradeItemOfferData
     */
    public function __construct(array $tradeItemOfferData)
    {
        $this->tradeItemOfferData = $tradeItemOfferData;
    }

    /**
     * Handle a job message
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $tradeItemOffer = $this->getUniqueTradeItemOffer();
            $tradeItemOfferRepository = new TradeItemOfferRepository($tradeItemOffer);
            $tradeItemOfferRepository->update($this->tradeItemOfferData);
        } catch (\Exception $e) {
            $this->failed($e);
        }
    }

    /**
     * @return string
     */
    public function getTradeItemId(): string
    {
        return $this->tradeItemOfferData['trade_item_id'];
    }

    /**
     * @return string
     */
    public function getSupplierId(): string
    {
        return $this->tradeItemOfferData['supplier_id'];
    }

    /**
     * @return string
     */
    public function getCustomerGroupId(): string
    {
        return $this->tradeItemOfferData['customer_group_id'];
    }

    /**
     * Fetch or create unique Trade Item Offer
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getUniqueTradeItemOffer(): Model
    {
        return TradeItemOffer::firstOrNew([
            'trade_item_id' => $this->tradeItemOfferData['trade_item_id'],
            'supplier_id' => $this->tradeItemOfferData['supplier_id'],
            'customer_group_id' => $this->tradeItemOfferData['customer_group_id'],
        ]);
    }
}

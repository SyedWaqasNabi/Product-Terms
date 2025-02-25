<?php

namespace App\Jobs;

use App\Models\TradeItemOffer;
use App\Repositories\TradeItemOfferRepository;
use App\Services\TradeItemOfferFamilyService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class TradeItemOfferUpdate
 * @package App\Jobs
 */
class TradeItemOfferUpdate extends AbstractJob implements ShouldQueue
{
    const IMPORT_QUEUE_NAME = 'IMPORT_TIO_PTS';

    public array $tradeItemOfferData;
    protected TradeItemOffer $tradeItemOffer;

    /**
     * Create a new job instance.
     * TradeItemOfferUpdate constructor.
     * @param TradeItemOffer $tradeItemOffer
     * @param array $tradeItemOfferData
     */
    public function __construct(TradeItemOffer $tradeItemOffer, array $tradeItemOfferData)
    {
        $this->tradeItemOffer = $tradeItemOffer;
        $this->tradeItemOfferData = $tradeItemOfferData;
    }

    /**
     * Handle the job
     */
    public function handle()
    {
        try {
            $oldTradeItemOfferId = $this->tradeItemOffer->trade_item_id;

            $tradeItemOfferRepository = new TradeItemOfferRepository($this->tradeItemOffer);
            $tradeItemOfferRepository->update($this->tradeItemOfferData);

            if (isset($this->tradeItemOfferData['trade_item_id'])
                && $this->tradeItemOfferData['trade_item_id'] != $oldTradeItemOfferId
            ) {
                $tradeItemOfferFamilyService = new TradeItemOfferFamilyService();
                $tradeItemOfferFamilyService->proceedTradeItemOfferFamily(
                    $oldTradeItemOfferId,
                    $tradeItemOfferRepository
                );
            }
        } catch (\Exception $e) {
            $this->failed($e);
        }
    }

    /**
     * @return int
     */
    public function getOfferId(): int
    {
        return $this->tradeItemOfferData['id'];
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
}

<?php

namespace App\Observers;

use App\Models\TradeItemOffer;
use App\Repositories\TradeItemOfferRepository;
use App\Services\TradeItemOfferFamilyService;

/**
 * Class TradeItemOfferObserver
 * @package App\Observers
 */
class TradeItemOfferObserver
{
    protected TradeItemOfferFamilyService $tradeItemOfferFamilyService;

    /**
     * TradeItemOfferObserver constructor.
     * @param TradeItemOfferFamilyService $tradeItemOfferFamilyService
     */
    public function __construct(TradeItemOfferFamilyService $tradeItemOfferFamilyService)
    {
        $this->tradeItemOfferFamilyService = $tradeItemOfferFamilyService;
    }

    /**
     * Handle to the TradeItemOffer "created" event.
     *
     * @param TradeItemOffer $tradeItemOffer
     */
    /**
     * @param TradeItemOffer $tradeItemOffer
     * @return bool|void
     */
    public function created(TradeItemOffer $tradeItemOffer)
    {
        if (!$tradeItemOffer->is_active
            || $tradeItemOffer->import_status == TradeItemOffer::STATUS_PROCESSING
            || $tradeItemOffer->import_status == TradeItemOffer::STATUS_COMPLETED
        ) {
            return true;
        }

        $tradeItemOfferRepository = new TradeItemOfferRepository($tradeItemOffer);

        try {
            $this->tradeItemOfferFamilyService->proceedTradeItemOfferFamily(
                $tradeItemOffer->trade_item_id,
                $tradeItemOfferRepository
            );
        } catch (\Exception $e) {
            //Log::info('Observer after delete id = ' . $tradeItemOffer->trade_item_id);
            return false;
        }
    }

    /**
     * Handle the TradeItemOffer "updating" event.
     *
     * @param TradeItemOffer $tradeItemOffer
     * @return bool|void
     */
    public function updated(TradeItemOffer $tradeItemOffer)
    {
        if ($tradeItemOffer->import_status == TradeItemOffer::STATUS_PROCESSING
            || $tradeItemOffer->import_status == TradeItemOffer::STATUS_COMPLETED
        ) {
            return true;
        }

        $tradeItemOfferRepository = new TradeItemOfferRepository($tradeItemOffer);
        $tradeItemOfferOld = $tradeItemOfferRepository->find($tradeItemOffer->id);
        if (!$tradeItemOffer->is_active && !$tradeItemOfferOld->is_active) {
            return true;
        }

        try {
            $this->tradeItemOfferFamilyService->proceedTradeItemOfferFamily(
                $tradeItemOffer->trade_item_id,
                $tradeItemOfferRepository
            );
        } catch (\Exception $e) {
            //Log::info('Observer after delete id = ' . $tradeItemOffer->trade_item_id);
            return false;
        }
    }

    /**
     * Handle the TradeItemOffer "deleted" event.
     *
     * @param TradeItemOffer $tradeItemOffer
     * @return bool|void
     */
    public function deleted(TradeItemOffer $tradeItemOffer)
    {
        $tradeItemOfferRepository = new TradeItemOfferRepository($tradeItemOffer);
        try {
            $this->tradeItemOfferFamilyService->proceedTradeItemOfferFamily(
                $tradeItemOffer->trade_item_id,
                $tradeItemOfferRepository
            );
        } catch (\Exception $e) {
            //Log::info('Observer after delete id = ' . $tradeItemOffer->trade_item_id);
            return false;
        }
    }
}

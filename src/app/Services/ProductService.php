<?php

namespace App\Services;

use App\Repositories\TradeItemOfferRepository;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    const PIM_IDENTIFIER_KEY = 'identifier';

    protected TradeItemOfferFamilyService $tradeItemOfferFamilyService;

    protected TradeItemOfferRepository $tradeItemOfferRepository;

    /**
     * TradeItemOfferObserver constructor.
     * @param TradeItemOfferFamilyService $tradeItemOfferFamilyService
     * @param TradeItemOfferRepository $tradeItemOfferRepository
     */
    public function __construct(
        TradeItemOfferFamilyService $tradeItemOfferFamilyService,
        TradeItemOfferRepository $tradeItemOfferRepository
    ) {
        $this->tradeItemOfferFamilyService = $tradeItemOfferFamilyService;
        $this->tradeItemOfferRepository    = $tradeItemOfferRepository;
    }

    /**
     * @param array $productEvent
     * @throws \Exception
     */
    public function proceedSyncProductEvent(array $productEvent)
    {
        if ($productEvent['enabled'] == 1) {
            $this->processTradeItemFamily($productEvent);
        } else {
            \Log::info(sprintf(
                'Akeneo product is not enabled. Payload %s',
                $productEvent
            ));
        }
    }

    /**
     * @param array $productEvent
     * @throws \Exception
     */
    public function proceedRemoveProductEvent(array $productEvent)
    {
        $this->processTradeItemFamily($productEvent);
    }

    /**
     * @param array $productEvent
     * @throws \Exception
     */
    private function processTradeItemFamily(array $productEvent)
    {
        $tradeItemId = $productEvent[self::PIM_IDENTIFIER_KEY];
        if (!empty($tradeItemId)) {
            $this->tradeItemOfferFamilyService->proceedTradeItemOfferFamily($tradeItemId, $this->tradeItemOfferRepository);
        }
    }
}

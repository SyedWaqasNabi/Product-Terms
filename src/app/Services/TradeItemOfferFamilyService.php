<?php

namespace App\Services;

use App\Models\TradeItemOffer;
use App\Repositories\TradeItemOfferRepository;
use Illuminate\Database\Eloquent\Collection;

class TradeItemOfferFamilyService
{
    const IMPORT_QUEUE_NAME = 'import-tio-family';
    const IMPORT_EXCHANGE_NAME = 'import-tio-exchange';

    /**
     * @param string $tradeItemId
     * @param TradeItemOfferRepository $tradeItemOfferRepository
     * @throws \Exception
     */
    public function proceedTradeItemOfferFamily(string $tradeItemId, TradeItemOfferRepository $tradeItemOfferRepository)
    {
        $tradeItemOfferFamily = $tradeItemOfferRepository->getTradeItemOfferFamilyById($tradeItemId);

        $jsonTradeItemOfferFamily = $this->prepareTradeItemOfferFamily($tradeItemId, $tradeItemOfferFamily);
        $this->publishTradeItemOfferFamily($jsonTradeItemOfferFamily);

        if ($tradeItemOfferFamily->count()) {
            $this->updateTradeItemOffersStatus($tradeItemOfferFamily);
        }
    }

    /**
     * @param Collection $tradeItemOfferFamily
     */
    private function updateTradeItemOffersStatus(Collection $tradeItemOfferFamily): void
    {
        $tradeItemOfferFamily->each(function ($tradeItemOffer) {
            $tradeItemOffer->update(['import_status' => TradeItemOffer::STATUS_PROCESSING]);
        });
    }

    /**
     * @param string $tradeItemId
     * @param Collection $tradeItemOfferFamily
     * @return string
     */
    private function prepareTradeItemOfferFamily(string $tradeItemId, Collection $tradeItemOfferFamily): string
    {
        return json_encode([
            'trade_item_id' => $tradeItemId,
            'trade_item_offers' => $tradeItemOfferFamily->toArray()
        ]);
    }

    /**
     * @param string $jsonTradeItemOfferFamily
     */
    private function publishTradeItemOfferFamily(string $jsonTradeItemOfferFamily): void
    {
        app('amqp')->publish($jsonTradeItemOfferFamily, self::IMPORT_QUEUE_NAME, [
            'exchange' => [
                'declare' => true,
                'type'    => 'direct',
                'name'    => self::IMPORT_EXCHANGE_NAME,
            ]
        ]);
    }
}

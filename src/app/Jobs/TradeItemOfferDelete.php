<?php

namespace App\Jobs;

use App\Models\TradeItemOffer;
use App\Repositories\TradeItemOfferRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class TradeItemOfferDelete
 * @package App\Jobs
 */
class TradeItemOfferDelete extends AbstractJob implements ShouldQueue
{
    const  DELETE_QUEUE_NAME = 'DELETE_TIO_PTS';

    protected TradeItemOffer $tradeItemOffer;

    /**
     * Create a new job instance.
     *
     * @param $tradeItemOffer
     */
    public function __construct($tradeItemOffer)
    {
        $this->tradeItemOffer = $tradeItemOffer;
    }

    /**
     * @return int
     */
    public function getOfferId(): int
    {
        return $this->tradeItemOffer->id;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $tradeItemOfferRepository = new TradeItemOfferRepository($this->tradeItemOffer);
            $tradeItemOfferRepository->delete();
        } catch (\Exception $e) {
            $this->failed($e);
        }
    }
}

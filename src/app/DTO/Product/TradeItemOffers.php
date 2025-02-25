<?php

namespace App\DTO\Product;

/**
 * Class TradeItemOffers
 * @package App\DTO\Product
 */
class TradeItemOffers implements TradeItemOffersInterface
{
    /** @var TradeItemOffer[] $tradeItemOffer */
    protected $tradeItemOffer = [];

    /**
     * @param TradeItemOffer $tradeItemOffer
     * @return $this
     */
    public function addTradeItemOffer(TradeItemOffer $tradeItemOffer): self
    {
        $this->tradeItemOffer[] = $tradeItemOffer;
        return $this;
    }


    /**
     * @return TradeItemOffer[]
     */
    public function getTradeItemOffer()
    {
        return $this->tradeItemOffer;
    }


    /**
     * @param TradeItemOffer[] $tradeItemOffer
     * @return $this
     */
    public function setTradeItemOffer($tradeItemOffer): self
    {
        $this->tradeItemOffer = $tradeItemOffer;
        return $this;
    }


    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'TradeItemOffer' => array_map(function (TradeItemOffer $data) {
                return $data->toArray();
            }, $this->tradeItemOffer),
        ];
    }
}

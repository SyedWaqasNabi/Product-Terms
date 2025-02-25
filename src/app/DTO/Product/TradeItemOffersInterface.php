<?php

namespace App\DTO\Product;

/**
 * Interface TradeItemOffersInterface
 * @package App\DTO\Product
 */
interface TradeItemOffersInterface
{
    public function addTradeItemOffer(TradeItemOffer $tradeItemOffer);

    public function getTradeItemOffer();

    public function setTradeItemOffer($tradeItemOffer);

    public function toArray(): array;
}

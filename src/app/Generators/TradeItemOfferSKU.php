<?php

namespace App\Generators;

/**
 * Class TradeItemOfferSKU
 * @package AppBundle\Generator
 */
class TradeItemOfferSKU
{
    /**
     * @param int $tradeItemId
     * @param int $supplierId
     * @param int $customerGroupId
     * @return string|null
     */
    public static function generateSku(int $tradeItemId, int $supplierId, int $customerGroupId): ?string
    {
        $tradeItemId = trim($tradeItemId);
        if (!$tradeItemId) {
            throw new \RuntimeException('Unable to generate Product SKU, Trade Item ID is empty.');
        }

        $supplierId = trim($supplierId);
        if (!$supplierId) {
            throw new \RuntimeException('Unable to generate Product SKU, Supplier ID is empty.');
        }

        $customerGroupId = trim($customerGroupId);
        if (!$customerGroupId) {
            throw new \RuntimeException('Unable to generate Product SKU, Supplier ID is empty.');
        }

        $tradeItemId36 = base_convert($tradeItemId, 10, 36);
        $supplierId36 = base_convert($supplierId, 10, 36);
        $customerGroupId36 = base_convert($customerGroupId, 10, 36);

        $sku = sprintf('%s.%s.%s', $tradeItemId36, $supplierId36, $customerGroupId36);
        $sku = strtoupper($sku);

        return $sku;
    }

    /**
     * @param int $tradeItemId
     * @param int $supplierId
     * @return string|null
     */
    public static function generateOldSku(int $tradeItemId, int $supplierId): ?string
    {
        $tradeItemId = trim($tradeItemId);
        if (!$tradeItemId) {
            throw new \RuntimeException('Unable to generate Product SKU, Trade Item ID is empty.');
        }

        $supplierId = trim($supplierId);
        if (!$supplierId) {
            throw new \RuntimeException('Unable to generate Product SKU, Supplier ID is empty.');
        }

        $tradeItemId36 = base_convert($tradeItemId, 10, 36);
        $supplierId36 = base_convert($supplierId, 10, 36);

        $sku = sprintf('%s.%s', $tradeItemId36, $supplierId36);
        $sku = strtoupper($sku);

        return $sku;
    }

    /**
     * @param string $sku
     * @return array|null
     */
    public static function dissipateSKU(string $sku): ?array
    {
        $sku = trim($sku);
        if (!$sku) {
            throw new \RuntimeException(
                'Unable to dissipate SKU to Trade Item ID, Supplier ID and Customer Group Id, SKU is empty.'
            );
        }

        list($tradeItemId, $supplierId36, $customerGroupId36) = array_pad(explode('.', $sku, 3), 3, null);

        return [
            'tradeItemId' => intval($tradeItemId, 36),
            'supplierId' => intval($supplierId36, 36),
            'customerGroupId36' => intval($customerGroupId36, 36),
        ];
    }

    /**
     * @param string $sku
     * @return array|null
     */
    public static function dissipateOldSKU(string $sku): ?array
    {
        $sku = trim($sku);
        if (!$sku) {
            throw new \RuntimeException(
                'Unable to dissipate old SKU to Trade Item ID and Supplier ID, old SKU is empty.'
            );
        }

        list($tradeItemId, $supplierId36) = array_pad(explode('.', $sku, 2), 2, null);

        return [
            'tradeItemId' => intval($tradeItemId, 36),
            'supplierId' => intval($supplierId36, 36),
        ];
    }
}

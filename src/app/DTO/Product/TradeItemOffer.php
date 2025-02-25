<?php

namespace App\DTO\Product;

/**
 * Class TradeItemOffer
 * @package App\DTO\Product
 */
class TradeItemOffer implements TradeItemOfferInterface
{
    /** @var int $id */
    protected int $id;

    /** @var string $internalName */
    protected string $internalName;

    /** @var string $supplierId */
    protected string $supplierId;

    /** @var string $supplierTradeItemNumber */
    protected string $supplierTradeItemNumber;

    /** @var string $globalTradeItemNumber */
    protected string $globalTradeItemNumber;

    /** @var string $customerGroupId */
    protected string $customerGroupId;

    /** @var float $netPrice */
    protected float $netPrice;

    /** @var string|null $currency */
    protected ?string $currency;

    /** @var float $salesUnit */
    protected float $salesUnit;

    /** @var string $stockKeepingUnit */
    protected string $stockKeepingUnit;

    /** @var string $oldStockKeepingUnit */
    protected string $oldStockKeepingUnit;

    /** @var int|null $maximumDeliveryTime */
    protected ?int $maximumDeliveryTime;

    /** @var int|null $minimumDeliveryTime */
    protected ?int $minimumDeliveryTime;

    /** @var string $deliveryTimeUnit */
    protected string $deliveryTimeUnit;

    /** @var int|null $minimumOrderQuantity */
    protected ?int $minimumOrderQuantity;

    /** @var int|null $maximumOrderQuantity */
    protected ?int $maximumOrderQuantity;

    /** @var string|null $validFrom */
    protected ?string $validFrom;

    /** @var string|null $validTo */
    protected ?string $validTo;

    /** @var bool|null $importStatus */
    protected ?bool $importStatus;

    /** @var bool $isActive */
    protected bool $isActive;

    /** @var bool $isWarehouseItem */
    protected bool $isWarehouseItem;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return string
     */
    public function getInternalName(): string
    {
        return $this->internalName;
    }


    /**
     * @param string $internalName
     * @return $this
     */
    public function setInternalName(string $internalName): self
    {
        $this->internalName = $internalName;
        return $this;
    }


    /**
     * @return string
     */
    public function getSupplierId(): string
    {
        return $this->supplierId;
    }


    /**
     * @param string $supplierId
     * @return $this
     */
    public function setSupplierId(string $supplierId): self
    {
        $this->supplierId = $supplierId;
        return $this;
    }


    /**
     * @return string
     */
    public function getSupplierTradeItemNumber(): string
    {
        return $this->supplierTradeItemNumber;
    }


    /**
     * @param string $supplierTradeItemNumber
     * @return $this
     */
    public function setSupplierTradeItemNumber(string $supplierTradeItemNumber): self
    {
        $this->supplierTradeItemNumber = $supplierTradeItemNumber;
        return $this;
    }


    /**
     * @return string
     */
    public function getGlobalTradeItemNumber(): string
    {
        return $this->globalTradeItemNumber;
    }


    /**
     * @param string $globalTradeItemNumber
     * @return $this
     */
    public function setGlobalTradeItemNumber(string $globalTradeItemNumber): self
    {
        $this->globalTradeItemNumber = $globalTradeItemNumber;
        return $this;
    }


    /**
     * @return string
     */
    public function getCustomerGroupId(): string
    {
        return $this->customerGroupId;
    }


    /**
     * @param string $customerGroupId
     * @return $this
     */
    public function setCustomerGroupId(string $customerGroupId): self
    {
        $this->customerGroupId = $customerGroupId;
        return $this;
    }


    /**
     * @return float
     */
    public function getNetPrice(): float
    {
        return $this->netPrice;
    }


    /**
     * @param float $netPrice
     * @return $this
     */
    public function setNetPrice(float $netPrice): self
    {
        $this->netPrice = $netPrice;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }


    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }


    /**
     * @return float
     */
    public function getSalesUnit(): float
    {
        return $this->salesUnit;
    }


    /**
     * @param float $salesUnit
     * @return $this
     */
    public function setSalesUnit(float $salesUnit): self
    {
        $this->salesUnit = $salesUnit;
        return $this;
    }


    /**
     * @return string
     */
    public function getStockKeepingUnit(): string
    {
        return $this->stockKeepingUnit;
    }


    /**
     * @param string $stockKeepingUnit
     * @return $this
     */
    public function setStockKeepingUnit(string $stockKeepingUnit): self
    {
        $this->stockKeepingUnit = $stockKeepingUnit;
        return $this;
    }


    /**
     * @return string
     */
    public function getOldStockKeepingUnit(): string
    {
        return $this->oldStockKeepingUnit;
    }


    /**
     * @param string $oldStockKeepingUnit
     * @return $this
     */
    public function setOldStockKeepingUnit(string $oldStockKeepingUnit): self
    {
        $this->oldStockKeepingUnit = $oldStockKeepingUnit;
        return $this;
    }


    /**
     * @return int
     */
    public function getMaximumDeliveryTime(): ?int
    {
        return $this->maximumDeliveryTime;
    }


    /**
     * @param int $maximumDeliveryTime
     * @return $this
     */
    public function setMaximumDeliveryTime(?int $maximumDeliveryTime): self
    {
        $this->maximumDeliveryTime = $maximumDeliveryTime;
        return $this;
    }


    /**
     * @return int
     */
    public function getMinimumDeliveryTime(): ?int
    {
        return $this->minimumDeliveryTime;
    }


    /**
     * @param int $minimumDeliveryTime
     * @return $this
     */
    public function setMinimumDeliveryTime(?int $minimumDeliveryTime): self
    {
        $this->minimumDeliveryTime = $minimumDeliveryTime;
        return $this;
    }


    /**
     * @return string
     */
    public function getDeliveryTimeUnit(): string
    {
        return $this->deliveryTimeUnit;
    }


    /**
     * @param string $deliveryTimeUnit
     * @return $this
     */
    public function setDeliveryTimeUnit(string $deliveryTimeUnit): self
    {
        $this->deliveryTimeUnit = $deliveryTimeUnit;
        return $this;
    }


    /**
     * @return int
     */
    public function getMinimumOrderQuantity(): ?int
    {
        return $this->minimumOrderQuantity;
    }


    /**
     * @param int $minimumOrderQuantity
     * @return $this
     */
    public function setMinimumOrderQuantity(?int $minimumOrderQuantity): self
    {
        $this->minimumOrderQuantity = $minimumOrderQuantity;
        return $this;
    }


    /**
     * @return int|null
     */
    public function getMaximumOrderQuantity(): ?int
    {
        return $this->maximumOrderQuantity;
    }


    /**
     * @param int $maximumOrderQuantity
     * @return $this
     */
    public function setMaximumOrderQuantity(?int $maximumOrderQuantity): self
    {
        $this->maximumOrderQuantity = $maximumOrderQuantity;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getValidFrom(): ?string
    {
        return $this->validFrom;
    }


    /**
     * @param string|null $validFrom
     * @return $this
     */
    public function setValidFrom(?string $validFrom): self
    {
        $this->validFrom = $validFrom;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getValidTo(): ?string
    {
        return $this->validTo;
    }


    /**
     * @param string|null $validTo
     * @return $this
     */
    public function setValidTo(?string $validTo): self
    {
        $this->validTo = $validTo;
        return $this;
    }


    /**
     * @return bool|null
     */
    public function getImportStatus(): ?bool
    {
        return $this->importStatus;
    }


    /**
     * @param bool|null $importStatus
     * @return $this
     */
    public function setImportStatus(?bool $importStatus): self
    {
        $this->importStatus = $importStatus;
        return $this;
    }


    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }


    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }


    /**
     * @return bool
     */
    public function getIsWarehouseItem(): bool
    {
        return $this->isWarehouseItem;
    }


    /**
     * @param bool $isWarehouseItem
     * @return $this
     */
    public function setIsWarehouseItem(bool $isWarehouseItem): self
    {
        $this->isWarehouseItem = $isWarehouseItem;

        return $this;
    }


    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'                         => $this->id,
            'internal_name'              => $this->internalName,
            'supplier_id'                => $this->supplierId,
            'supplier_trade_item_number' => $this->supplierTradeItemNumber,
            'customer_group_id'          => $this->customerGroupId,
            'net_price'                  => $this->netPrice,
            'currency'                   => $this->currency,
            'sales_unit'                 => $this->salesUnit,
            'stock_keeping_unit'         => $this->stockKeepingUnit,
            'old_stock_keeping_unit'     => $this->oldStockKeepingUnit,
            'maximum_delivery_time'      => $this->maximumDeliveryTime,
            'minimum_delivery_time'      => $this->minimumDeliveryTime,
            'delivery_time_unit'         => $this->deliveryTimeUnit,
            'minimum_order_quantity'     => $this->minimumOrderQuantity,
            'maximum_order_quantity'     => $this->maximumOrderQuantity,
            'valid_from'                 => $this->validFrom,
            'valid_to'                   => $this->validTo,
            'import_status'              => $this->importStatus,
            'is_active'                  => $this->isActive,
            'is_warehouse_item'          => $this->isWarehouseItem,
        ];
    }
}

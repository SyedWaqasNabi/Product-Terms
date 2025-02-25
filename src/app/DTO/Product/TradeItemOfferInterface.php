<?php

namespace App\DTO\Product;

/**
 * Interface TradeItemOfferInterface
 * @package App\DTO\Product
 */
interface TradeItemOfferInterface
{
    public function getId(): int;

    public function setId(int $id): self;

    public function getInternalName(): string;

    public function setInternalName(string $internalName): self;

    public function getSupplierId(): string;

    public function setSupplierId(string $supplierId): self;

    public function getSupplierTradeItemNumber(): string;

    public function setSupplierTradeItemNumber(string $supplierTradeItemNumber): self;

    public function getGlobalTradeItemNumber(): string;

    public function setGlobalTradeItemNumber(string $globalTradeItemNumber): self;

    public function getCustomerGroupId(): string;

    public function setCustomerGroupId(string $customerGroupId): self;

    public function getNetPrice(): float;

    public function setNetPrice(float $netPrice): self;

    public function getCurrency(): ?string;

    public function setCurrency(?string $currency): self;

    public function getSalesUnit(): float;

    public function setSalesUnit(float $salesUnit): self;

    public function getStockKeepingUnit(): string;

    public function setStockKeepingUnit(string $stockKeepingUnit): self;

    public function getOldStockKeepingUnit(): string;

    public function setOldStockKeepingUnit(string $oldStockKeepingUnit): self;

    public function getMaximumDeliveryTime(): ?int;

    public function setMaximumDeliveryTime(?int $maximumDeliveryTime): self;

    public function getMinimumDeliveryTime(): ?int;

    public function setMinimumDeliveryTime(?int $minimumDeliveryTime): self;

    public function getDeliveryTimeUnit(): string;

    public function setDeliveryTimeUnit(string $deliveryTimeUnit): self;

    public function getMinimumOrderQuantity(): ?int;

    public function setMinimumOrderQuantity(?int $minimumOrderQuantity): self;

    public function getMaximumOrderQuantity(): ?int;

    public function setMaximumOrderQuantity(?int $maximumOrderQuantity): self;

    public function getValidFrom(): ?string;

    public function setValidFrom(?string $validFrom): self;

    public function getValidTo(): ?string;

    public function setValidTo(?string $validTo): self;

    public function getImportStatus(): ?bool;

    public function setImportStatus(?bool $importStatus): self;

    public function getIsActive(): bool;

    public function setIsActive(bool $isActive): self;

    public function getIsWarehouseItem(): bool;

    public function setIsWarehouseItem(bool $isWarehouseItem): self;
}

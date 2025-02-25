<?php

namespace App\Services\Filters;

/**
 * Class TradeItemOfferFilter
 * @package App\Services\Filters
 */
class TradeItemOfferFilter extends QueryFilter
{
    /**
     * @param string $id
     */
    public function id(string $id)
    {
        $this->builder->where('id', '=', $id);
    }

    /**
     * @param string $tradeItemId
     */
    public function tradeItemId(string $tradeItemId)
    {
        $this->builder->where('trade_item_id', '=', $tradeItemId);
    }

    /**
     * @param string $internalName
     */
    public function internalName(string $internalName)
    {
        $this->builder->where('internal_name', 'like', '%'.$internalName.'%');
    }

    /**
     * @param string $supplierId
     */
    public function supplierId(string $supplierId)
    {
        $this->builder->where('supplier_id', '=', $supplierId);
    }

    /**
     * @param int $supplierTradeItemNumber
     */
    public function supplierTradeItemNumber(int $supplierTradeItemNumber)
    {
        $this->builder->where('supplier_trade_item_number', '=', $supplierTradeItemNumber);
    }

    /**
     * @param string $customerGroupId
     */
    public function customerGroupId(string $customerGroupId)
    {
        $this->builder->where('customer_group_id', '=', $customerGroupId);
    }

    /**
     * @param float $netPrice
     */
    public function netPrice(float $netPrice)
    {
        $this->builder->where('net_price', '=', $netPrice);
    }

    /**
     * @param string $currency
     */
    public function currency(string $currency)
    {
        $this->builder->where('currency', '=', $currency);
    }

    /**
     * @param string $salesUnit
     */
    public function salesUnit(string $salesUnit)
    {
        $this->builder->where('sales_unit', '=', $salesUnit);
    }

    /**
     * @param string $stockKeepingUnit
     */
    public function stockKeepingUnit(string $stockKeepingUnit)
    {
        $this->builder->where('stock_keeping_unit', '=', $stockKeepingUnit);
    }

    /**
     * @param string $oldStockKeepingUnit
     */
    public function oldStockKeepingUnit(string $oldStockKeepingUnit)
    {
        $this->builder->where('old_stock_keeping_unit', '=', $oldStockKeepingUnit);
    }

    /**
     * @param int $maximumDeliveryTime
     */
    public function maximumDeliveryTime(int $maximumDeliveryTime)
    {
        $this->builder->where('maximum_delivery_time', '=', $maximumDeliveryTime);
    }

    /**
     * @param int $minimumDeliveryTime
     */
    public function minimumDeliveryTime(int $minimumDeliveryTime)
    {
        $this->builder->where('minimum_delivery_time', '=', $minimumDeliveryTime);
    }

    /**
     * @param string $deliveryTimeUnit
     */
    public function deliveryTimeUnit(string $deliveryTimeUnit)
    {
        $this->builder->where('delivery_time_unit', '=', $deliveryTimeUnit);
    }

    /**
     * @param int $minimumOrderQuantity
     */
    public function minimumOrderQuantity(int $minimumOrderQuantity)
    {
        $this->builder->where('minimum_order_quantity', '=', $minimumOrderQuantity);
    }

    /**
     * @param int $maximumOrderQuantity
     */
    public function maximumOrderQuantity(int $maximumOrderQuantity)
    {
        $this->builder->where('maximum_order_quantity', '=', $maximumOrderQuantity);
    }

    /**
     * @param string $importStatus
     */
    public function importStatus(string $importStatus)
    {
        $this->builder->where('import_status', '=', $importStatus);
    }

    /**
     * @param string $createdAfter
     */
    public function createdAt(string $createdAfter)
    {
        $this->builder->whereDate('created_at', '>', $createdAfter);
    }

    /**
     * @param string $createdBefore
     */
    public function createdBefore(string $createdBefore)
    {
        $this->builder->whereDate('created_at', '<', $createdBefore);
    }

    /**
     * @param string $updatedAfter
     */
    public function updatedAfter(string $updatedAfter)
    {
        $this->builder->whereDate('updated_at', '>', $updatedAfter);
    }

    /**
     * @param string $updatedBefore
     */
    public function updatedBefore(string $updatedBefore)
    {
        $this->builder->whereDate('updated_at', '<', $updatedBefore);
    }

    /**
     * @param string $isActive
     */
    public function isActive(string $isActive)
    {
        $this->builder->where('is_active', '=', $isActive);
    }

    /**
     * @param string $isWarehouseItem
     */
    public function isWarehouseItem(string $isWarehouseItem)
    {
        $this->builder->where('is_warehouse_item', '=', $isWarehouseItem);
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortId($type = null)
    {
        return $this->builder->orderBy('id', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortCreatedAt($type = null)
    {
        return $this->builder->orderBy('created_at', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortUpdatedAt($type = null)
    {
        return $this->builder->orderBy('updated_at', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortMaximumDeliveryTime($type = null)
    {
        return $this->builder->orderBy('maximum_delivery_time', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortMinimumDeliveryTime($type = null)
    {
        return $this->builder->orderBy('minimum_delivery_time', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortSupplierId($type = null)
    {
        return $this->builder->orderBy('supplier_id', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortTradeItemId($type = null)
    {
        return $this->builder->orderBy('trade_item_id', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortInternalName($type = null)
    {
        return $this->builder->orderBy('internal_name', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortSupplierTradeItemNumber($type = null)
    {
        return $this->builder->orderBy('supplier_trade_item_number', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortCustomerGroupId($type = null)
    {
        return $this->builder->orderBy('customer_group_id', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortNetPrice($type = null)
    {
        return $this->builder->orderBy('net_price', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortCurrency($type = null)
    {
        return $this->builder->orderBy('currency', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortSalesUnit($type = null)
    {
        return $this->builder->orderBy('sales_unit', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortStockKeepingUnit($type = null)
    {
        return $this->builder->orderBy('stock_keeping_unit', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortOldStockKeepingUnit($type = null)
    {
        return $this->builder->orderBy('old_stock_keeping_unit', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortDeliveryTimeUnit($type = null)
    {
        return $this->builder->orderBy('delivery_time_unit', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortValidFrom($type = null)
    {
        return $this->builder->orderBy('valid_from', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortValidTo($type = null)
    {
        return $this->builder->orderBy('valid_to', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortImportStatus($type = null)
    {
        return $this->builder->orderBy('import_status', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortIsActive($type = null)
    {
        return $this->builder->orderBy('is_active', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortIsWarehouseItem($type = null)
    {
        return $this->builder->orderBy('is_warehouse_item', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }

    /**
     * @param string $filter
     * @return string|null
     */
    public function getFilter(string $filter): ?string
    {
        $filterBag = $this->request->query->get('filter');
        if (isset($filterBag[$filter])) {
            $filterValue = $filterBag[$filter];
        }

        return $filterValue ?? null;
    }

    /**
     * @param string $limit
     */
    public function limit(string $limit)
    {
        $this->builder->limit($limit);
    }
}

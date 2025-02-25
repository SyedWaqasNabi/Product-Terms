<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TradeItemOffer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'trade_item_offer',
            'attributes' => [
                'trade_item_id' => (int)$this->trade_item_id,
                'internal_name' => $this->internal_name,
                'supplier_id' => (int)$this->supplier_id,
                'supplier_trade_item_number' => $this->supplier_trade_item_number,
                'customer_group_id' => (int)$this->customer_group_id,
                'net_price' => (float) $this->net_price,
                'currency' => strtoupper($this->currency),
                'sales_unit' => (float) $this->sales_unit,
                'stock_keeping_unit' => $this->stock_keeping_unit,
                'old_stock_keeping_unit' => $this->old_stock_keeping_unit,
                'maximum_delivery_time' => (int) $this->maximum_delivery_time,
                'minimum_delivery_time' => (int) $this->minimum_delivery_time,
                'delivery_time_unit' => $this->delivery_time_unit,
                'minimum_order_quantity' => $this->minimum_order_quantity,
                'maximum_order_quantity' => $this->maximum_order_quantity,
                'valid_from' => $this->valid_from,
                'valid_to' => $this->valid_to,
                'import_status' => $this->import_status,
                'is_active' => (boolean) $this->is_active,
                'is_warehouse_item' => (boolean) $this->is_warehouse_item,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        ];
    }
}

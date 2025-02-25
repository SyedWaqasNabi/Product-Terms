<?php

namespace App\Models;

use App\Services\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TradeItemOffer
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $trade_item_id
 * @property string $internal_name
 * @property int $supplier_id
 * @property string $supplier_trade_item_number
 * @property int $customer_group_id
 * @property float $net_price
 * @property string|null $currency
 * @property float $sales_unit
 * @property string $stock_keeping_unit
 * @property string $old_stock_keeping_unit
 * @property int|null $maximum_delivery_time
 * @property int|null $minimum_delivery_time
 * @property string $delivery_time_unit
 * @property int|null $minimum_order_quantity
 * @property int|null $maximum_order_quantity
 * @property string|null $valid_from
 * @property string|null $valid_to
 * @property string|null $import_status
 * @property string|null $hash
 * @property int $is_active
 * @property int $is_warehouse_item
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereTradeItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereCustomerGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereDeliveryTimeUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereImportStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereInternalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereIsWarehouseItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereMaximumDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereMaximumOrderQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereMinimumDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereMinimumOrderQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereNetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereOldStockKeepingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereSalesUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereStockKeepingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereSupplierTradeItemNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer limit()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TradeItemOffer applyFilters(\App\Services\Filters\QueryFilter $filter)
 */
class TradeItemOffer extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_NOT_IMPORTED = 'not imported';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';

    use Filterable;

    /**
     * The attributes that can be mass assigned
     * @var array
     */
    protected $fillable = [
        'trade_item_id',
        'internal_name',
        'supplier_trade_item_number',
        'net_price',
        'currency',
        'sales_unit',
        'sales_unit_type',
        'old_stock_keeping_unit',
        'maximum_delivery_time',
        'minimum_delivery_time',
        'delivery_time_unit',
        'maximum_order_quantity',
        'minimum_order_quantity',
        'valid_from',
        'valid_to',
        'import_status',
        'is_active',
        'is_warehouse_item',
        'hash',
    ];

    /**
     * The attributes that are mass blocked.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trade_item_offer';
}

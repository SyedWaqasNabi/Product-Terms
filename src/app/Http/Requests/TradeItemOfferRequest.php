<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TradeItemOfferRequest
 * @package App\Http\Requests
 */
class TradeItemOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trade_item_id'              => 'required|numeric',
            'supplier_id'                => 'required|numeric',
            'customer_group_id'          => 'required|numeric',
            'currency'                   => 'required|size:3',
            'sales_unit'                 => 'required|numeric',
            'net_price'                  => 'required|numeric',
            'minimum_delivery_time'      => 'required|numeric',
            'maximum_delivery_time'      => 'required|numeric',
            'maximum_order_quantity'     => 'required|numeric',
            'minimum_order_quantity'     => 'required|numeric',
            'internal_name'              => 'required|string',
            'supplier_trade_item_number' => 'required|string',
            'is_active'                  => 'required|boolean',
            'is_warehouse_item'          => 'required|boolean'
        ];
    }
}

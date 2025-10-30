<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read integer $cart_item_id
 */
class ProductItemRemoveFromCartRequest extends FormRequest
{
    protected $redirectRoute = 'web.product-item.list';

    public function rules(): array
    {
        return [
            'cart_item_id' => 'required|integer',
        ];
    }
}
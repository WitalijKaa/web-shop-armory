<?php

namespace App\Request;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read integer $product_id
 */
class ProductItemAddToCartRequest extends FormRequest
{
    protected $redirectRoute = 'web.product-item.list';

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer',
        ];
    }
}
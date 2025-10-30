<?php

namespace App\Models\Shop\Product;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $product_id
 * @property int $amount
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereAmount($value)
 *
 * @property-read Product $product
 * 
 * @mixin \Eloquent
 */
class ProductItem extends \Eloquent
{
    public const string TABLE_NAME = 'product_item';
    protected $table = self::TABLE_NAME;

    public $timestamps = false;
    protected $guarded = ['id'];
    public function product(): HasOne { return $this->hasOne(Product::class, 'id', 'product_id'); }
    protected function casts(): array
    {
        return [
            'price' => 'float',
        ];
    }
}

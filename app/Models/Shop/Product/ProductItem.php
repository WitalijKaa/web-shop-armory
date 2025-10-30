<?php

namespace App\Models\Shop\Product;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $product_id
 * @property int $amount
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
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

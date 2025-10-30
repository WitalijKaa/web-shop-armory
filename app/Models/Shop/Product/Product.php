<?php

namespace App\Models\Shop\Product;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 *
 * @mixin \Eloquent
 */
class Product extends \Eloquent
{
    public const string TABLE_NAME = 'product';
    protected $table = self::TABLE_NAME;

    protected $guarded = ['id'];
    protected function casts(): array
    {
        return [
            'price' => 'float',
        ];
    }
}

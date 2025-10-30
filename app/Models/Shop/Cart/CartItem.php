<?php

namespace App\Models\Shop\Cart;

use App\Models\Shop\Product\ProductItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $cart_id
 * @property int $product_item_id
 * @property int $amount
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereAmount($value)
 *
 * @property-read Cart $cart
 * @property-read ProductItemModel $productItem
 *
 * @mixin \Eloquent
 */
class CartItem extends \Eloquent
{
    public const string TABLE_NAME = 'cart_item';
    protected $table = self::TABLE_NAME;

    public $timestamps = false;
    protected $guarded = ['id'];

    public function removeFromCart(int $amount = 1): void
    {
        DB::transaction(function () use ($amount) {
            $items = ProductItem::whereProductId($this->product_item_id)
                ->lockForUpdate()
                ->first();

            $amount = $this->amount < $amount ? $this->amount : $amount;

            $items->amount += $amount;
            $items->amount_reserved -= $amount;

            $this->amount -= $amount;

            $items->save();

            if ($this->amount > 0) {
                $this->save();
            } else {
                $this->delete();
            }
        });
    }

    public static function itemsToAddToCart(int $cartID, int $productItemID): static
    {
        $model = static::whereCartId($cartID)->whereProductItemId($productItemID)->lockForUpdate()->first();
        if (!$model) {
            $model = new static();
            $model->cart_id = $cartID;
            $model->product_item_id = $productItemID;
            $model->amount = 0;
        }
        return $model;
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItemModel::class, 'cart_item_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'amount' => 'int',
        ];
    }
}

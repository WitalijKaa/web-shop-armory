<?php

namespace App\Models\Shop\Cart;

use App\Models\Shop\Product\ProductItem;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $client_uuid
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereClientUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereStatus($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CartItem> $items
 *
 * @mixin \Eloquent
 */
class Cart extends \Eloquent
{
    public const string TABLE_NAME = 'cart';
    protected $table = self::TABLE_NAME;

    public function addToCartByProductID(int $id, int $amount = 1) {
        if (!$this->id) {
            $this->save();
        }

        DB::transaction(function () use ($id, $amount) {
            $items = ProductItem::whereProductId($id)
                ->where('amount', '>=', $amount)
                ->lockForUpdate()
                ->first();

            if ($items) {
                $items->amount -= $amount;
                $items->amount_reserved += $amount;

                $cartItems = CartItem::itemsToAddToCart($this->id, $items->id);
                $cartItems->amount += $amount;

                $items->save();
                $cartItems->save();
            }
        });
    }

    public function productsItemsIDs(): array
    {
        return CartItem::whereCartId($this->id)->pluck('product_item_id')->toArray();
    }

    public function setInCartAmount(Collection $productItems): void
    {
        $this->items->each(function (CartItem $cartItem) use ($productItems) {
            if ($itemInCart = $productItems->filter(fn (ProductItem $productItem) => $productItem->id == $cartItem->product_item_id)->first()) {
                $itemInCart->inCartItem = $cartItem;
            }
        });
    }

    protected $guarded = ['id'];
    public function items(): HasMany { return $this->hasMany(CartItem::class, 'cart_id', 'id'); }
    protected function casts(): array
    {
        return [
            'status' => CartStatusEnum::class,
        ];
    }
}

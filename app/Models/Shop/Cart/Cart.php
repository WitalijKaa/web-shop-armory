<?php

namespace App\Models\Shop\Cart;

use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductItem;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @property int $id
 * @property string $client_uuid
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereClientUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereStatus($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CartItem> $items
 * @property-read bool $mayReserve
 * @property-read bool $mayPay
 * @property-read float $price
 * @property-read float $priceReserved
 *
 * @mixin \Eloquent
 */
class Cart extends \Eloquent
{
    public const string TABLE_NAME = 'cart';
    protected $table = self::TABLE_NAME;

    protected $appends = ['mayReserve', 'mayPay', 'price', 'priceReserved'];

    public function addToCartByProductID(int $id, int $amount = 1): void
    {
        if (!$this->id) {
            $this->save();
        }

        $items = ProductItem::whereProductId($id)
            ->where('amount', '>=', $amount)
            ->first();

        if ($items) {
            $cartItems = CartItem::itemsToAddToCart($this->id, $items->id);

            if ($items->amount >= $cartItems->amount + $amount) {
                $cartItems->amount += $amount;
                $cartItems->save();
            }
        }
    }

    public function reserveToCartByProductItemID(CartItem $cartItems): void
    {
        $productID = ProductItem::whereId($cartItems->product_item_id)->select('product_id')->first()->product_id;
        $price = Product::whereId($productID)->select('price')->first()->price;

        DB::transaction(function () use ($cartItems, $price) {
            $items = ProductItem::whereId($cartItems->product_item_id)
                ->lockForUpdate()
                ->first();

            $actualAmount = $cartItems->amount > $items->amount ? $items->amount : $cartItems->amount;

            if ($actualAmount < 1) {
                $cartItems->delete();
                return;
                // inform client that nothing is available
            }
            
            if ($actualAmount < $cartItems->amount) {
                // inform client that not everything is available
            }

            $items->amount -= $actualAmount;
            $items->amount_reserved += $actualAmount;

            $cartItems->amount = $actualAmount;
            $cartItems->price = $price;

            $items->save();
            $cartItems->save();
        });
    }

    public function reserveCartItems(): void
    {
        if ($this->status != CartStatusEnum::potential) {
            return;
        }

        $this->items->each(function (CartItem $cartItems) {
            $this->reserveToCartByProductItemID($cartItems);
        });
        
        $this->status = CartStatusEnum::reserved;
        $this->save();
    }

    public function payCartItems(): void
    {
        if ($this->status != CartStatusEnum::reserved) {
            throw new \Exception('Pay critical error');
        }

        $this->items->each(function (CartItem $cartItems) {
            do {
                DB::transaction(function () use ($cartItems) {
                        $productItems = ProductItem::whereId($cartItems->product_item_id)->lockForUpdate()->first();

                        $amountReserved = $productItems->amount_reserved - $cartItems->amount;
                        if ($amountReserved < 0) {
                            // alert critical error
                            $amountReserved = 0;
                        }

                        ProductItem::whereId($cartItems->product_item_id)
                            ->whereAmountReserved($productItems->amount_reserved)
                            ->update(['amount_reserved' => $amountReserved]);
                    });
                    $success = true;
                try {
                    
                }
                catch(\Throwable) {
                    $success = false;
                    // add $limit and if something very wrong inform client
                }
            } while (!$success);
        });
        
        $this->status = CartStatusEnum::paid;
        $this->paid_at = now();
        $this->save();
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

    public function getMayReserveAttribute() {
        return $this->items->count() && $this->status == CartStatusEnum::potential;
    }

    public function getMayPayAttribute() {
        return $this->status == CartStatusEnum::reserved;
    }

    public function getPriceAttribute() {
        return $this->items->sum(fn (CartItem $cartItem) => $cartItem->amount * $cartItem->productItem->product->price);
    }

    public function getPriceReservedAttribute() {
        return $this->items->sum(fn (CartItem $cartItem) => $cartItem->amount * $cartItem->price);
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

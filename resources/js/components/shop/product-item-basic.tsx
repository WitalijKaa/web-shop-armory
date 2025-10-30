import { Link } from '@inertiajs/react';
import { addToCart, removeFromCart } from '@/routes/web/product-item';

export default function ProductItemBasic({ item, ix, allowRemove = false }) {
  return (
    <div className="w-[400px] rounded-2xl border border-[#19140035] bg-white p-6 shadow-sm my-8 ml-8">
      <h4 className="text-4l font-bold text-center my-4">{ix + 1}. {item.product.name}</h4>
      <h4 className="text-4l font-bold text-center my-4">{item.product.price.toFixed(2)} $</h4>
      {item.inCartItem?.amount > 0 && (
        <h4 className="text-4l text-center my-4">in cart {item.inCartItem.amount}</h4>
      )}
      <div className="flex flex-row justify-between">
        {(item.amount - (item.inCartItem?.amount | 0)) > 0 && (
        <Link
          href={addToCart()}
          method="post"
          data={{ product_id: item.product_id }}
          className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
          Add to cart
        </Link>
        )}
        
        {allowRemove && item.inCartItem?.amount > 0 &&(
          <Link
            href={removeFromCart()}
            method="delete"
            data={{ cart_item_id: item.inCartItem.id }}
            className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
          >
            Remove from cart
          </Link>
        )}
      </div>
    </div>
  )
}

import { Link } from '@inertiajs/react';
import { addToCart } from '@/routes/web/product-item';

export default function ProductItemBasic({ item, ix }) {
  return (
    <div className="w-1/4 rounded-2xl border border-[#19140035] bg-white p-6 shadow-sm my-8 ml-8">
      <h4 className="text-4l font-bold text-center my-4">{ix + 1}. {item.product.name}</h4>
      <h4 className="text-4l font-bold text-center my-4">{item.product.price.toFixed(2)} $</h4>
      <Link
        href={addToCart()}
        method="post"
        data={{ product_id: item.product_id }}
        className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
      >
        Add to cart
      </Link>
    </div>
  )
}

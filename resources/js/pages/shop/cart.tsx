import { Head, Link } from '@inertiajs/react'
import ProductItemBasic from '@/components/shop/product-item-basic'
import CartItemSimple from '@/components/shop/cart-item-simple'
import { list, cartReservation, cartPayment } from '@/routes/web/product-item';

export default function List({ items, cart, cart_reserved }) {
  return (
    <>
      <Head title="Armory SHOP client cart" />
      <h1 className="text-4xl font-bold text-center my-8">Armory SHOP client cart</h1>
      {cart.price && (
        <h2 className="text-4xl font-bold text-center my-8">Total price {cart.price.toFixed(2)} $</h2>
      )}
      <div className="flex flex-wrap justify-center gap-6">
        {items.map((item, ix) => (
          <ProductItemBasic key={item.id} item={item} ix={ix} allowRemove />
        ))}
      </div>
      <div className="flex flex-col items-center">
        {cart.mayReserve && !cart_reserved && (
          <Link href={cartReservation()} className="mt-20 inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]" >
            Reserve items 
          </Link>
        )}

        {cart_reserved && (
          <div className="flex flex-col items-center mt-20">
            <h2 className="text-4xl font-bold text-center my-4">Reserved cart</h2>
            <h2 className="text-4xl font-bold text-center my-4">Total price {cart_reserved.priceReserved} $</h2>
            <Link href={cartPayment()} className="mb-8 mt-4 inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]" >
              Buy items for {cart_reserved.priceReserved} $
            </Link>
            <div className="flex flex-wrap justify-center gap-3">
              {cart_reserved.items.map((item, ix) => (
                <CartItemSimple key={item.id} item={item} ix={ix} />
              ))}
            </div>
          </div>
        )}

        <Link href={list()} className="mt-40 inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]" >
          Back to SHOP Armory 
        </Link>

        <div className="mt-20">User {cart.client_uuid}</div>
      </div>
    </>
  )
}


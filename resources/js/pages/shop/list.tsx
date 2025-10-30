import { Head } from '@inertiajs/react'
import ProductItemBasic from '@/components/shop/product-item-basic'

export default function List({ items, cart }) {
  return (
    <>
      <Head title="Armory SHOP" />
      <h1 className="text-4xl font-bold text-center my-8">Armory SHOP</h1>
      <div className="flex flex-col items-center">
        {items.map((item, ix) => (
          <ProductItemBasic key={item.id} item={item} ix={ix} />
        ))}
      </div>

      <div className="mt-20">User {cart.client_uuid}</div>
    </>
  )
}

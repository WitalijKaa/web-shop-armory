import { Head } from '@inertiajs/react'
import ProductItemAdmin from '@/components/shop/product-item-admin'

export default function List({ products, carts }) {
  return (
    <>
      <Head title="ADMIN PAGE" />
      <h1 className="text-4xl font-bold text-center my-8">ADMIN PAGE</h1>
      <div className="flex flex-wrap justify-center gap-6">
        {products.map((item, ix) => (
          <ProductItemAdmin key={item.id} item={item} ix={ix} />
        ))}
      </div>
      <div className="p-10">
        {carts.map((item, ix) => (
            <p>Cart <small>{item.client_uuid}</small> -- paid at {item.paid_at} -- COSTS -- <strong>{item.price_reserved.toFixed(2)} $</strong></p>
        ))}
      </div>
    </>
  )
}

import { Head } from '@inertiajs/react'
import ProductItemAdmin from '@/components/shop/product-item-admin'

export default function List({ items }) {
  return (
    <>
      <Head title="ADMIN PAGE" />
      <h1 className="text-4xl font-bold text-center my-8">ADMIN PAGE</h1>
      <div className="flex flex-col items-center">
        {items.map((item, ix) => (
          <ProductItemAdmin key={item.id} item={item} ix={ix} />
        ))}
      </div>
    </>
  )
}

import { Head, Link } from '@inertiajs/react'
import ProductItemBasic from '@/components/shop/product-item-basic'
import { list } from '@/routes/web/product-item';

export default function List({ items, cart_uuid }) {
  return (
    <>
      <Head title="Armory SHOP client cart" />
      <h1 className="text-4xl font-bold text-center my-8">Armory SHOP client cart</h1>
      <div className="flex flex-col items-center">
      {items.map((item, ix) => (
        <ProductItemBasic key={item.id} item={item} ix={ix} allowRemove />
      ))}
      </div>

      <div className="flex flex-col items-center">
        <Link href={list()} className="mt-20 inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]" >
          Back to SHOP Armory 
        </Link>

        <div className="mt-20">User {cart_uuid}</div>
      </div>
    </>
  )
}


export default function CartItemSimple({ item, ix }) {
  let priceInCart = item.amount * item.price;

  return (
    <div className="w-[600px] rounded-2xl border border-[#19140035] bg-white p-6 shadow-sm my-8 ml-8">
      <h4 className="text-4l font-bold text-center my-4">{ix + 1}. {item.product_item.product.name}</h4>
      <h4 className="text-4l font-bold text-center my-4">{item.price.toFixed(2)} $</h4>
      <h4 className="text-4l text-center my-4">
          reserved {item.amount}
          {item.amount > 1 && (
            <span> - {priceInCart.toFixed(2)} $</span>
          )}
        </h4>
    </div>
  )
}

export default function ProductItemAdmin({ item, ix }) {
  return (
    <div className="w-[300px] rounded-2xl border border-[#19140035] bg-white p-6 shadow-sm my-8 ml-8">
      <h4 className="text-4l font-bold text-center my-4">{ix + 1}. {item.product.name}</h4>
      <h4 className="text-4l font-bold text-center my-4">{item.product.price.toFixed(2)} $</h4>
      <h4 className="text-4l text-center my-4">available {item.amount}</h4>
      {!!item.amount_reserved && (
        <div>
          <h4 className="text-4l text-center my-4">reserved {item.amount_reserved} </h4>
        </div>
      )}
    </div>
  )
}

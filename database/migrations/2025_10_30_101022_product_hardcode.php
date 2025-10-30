<?php

use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;

return new class extends Migration
{
    public function up(): void
    {
        $model = new Product();
        $model->name = 'Sword';
        $model->price = 200.20;
        $model->save();

        $model = new Product();
        $model->name = 'Shield';
        $model->price = 150.10;
        $model->save();

        $model = new Product();
        $model->name = 'Hammer';
        $model->price = 404.0;
        $model->save();

        $model = new Product();
        $model->name = 'Spear';
        $model->price = 50.95;
        $model->save();

        Product::chunk(50, function (Collection $clt) {
            $clt->each(function (Product $model) {
                $items = new ProductItem();
                $items->amount = rand(50, 123);
                $items->product_id = $model->id;
                $items->save();
            });
        });

        ProductItem::whereProductId(Product::whereName('Spear')->first()->id)->update(['amount' => 5]);
    }

    public function down(): void
    {
        ProductItem::where('id', '>', 0)->delete();
        Product::where('id', '>', 0)->delete();
    }
};

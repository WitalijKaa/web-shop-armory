<?php

namespace WebShop\Notifications\Notifications\ProductItem;

class ProductItemVeryLowAmountNotification extends ProductItemLowAmountNotification
{
    public function actionID(): ?string
    {
        return 'prod_zero_' . $this->productItem->product_id;
    }
}

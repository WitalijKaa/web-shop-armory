<?php

namespace WebShop\Notifications\Notifications\ProductItem;

class ProductItemVeryLowAmountNotification extends ProductItemLowAmountNotification
{
    public function actionID(): string
    {
        return 'low_very_product_amount_' . $this->productItem->product_id;
    }
}

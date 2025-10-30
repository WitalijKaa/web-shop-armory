<?php

namespace App\Models\Shop\Cart;

enum CartStatusEnum: int
{
    case potential = 1;
    case reserved = 2;
    case paid = 3;
    case delivering = 4;
    case finished = 5;
}

<?php

namespace App\Cart;

use App\Entity\Figurines;

class CartItem {
    public $figurine;
    public $qty;

    public function __construct(Figurines $figurine, int $qty)
    {
        $this->figurine = $figurine;
        $this->qty = $qty;
    }

    public function getTotal() : int {
        return $this->figurine->getPrice() * $this->qty / 100;
    }
}
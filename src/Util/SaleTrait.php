<?php
namespace PyramidSelling\Util;
///// traits for sale and profit /////
trait SaleTrait
{
    public function sellProductOrServices($product, $quantity): void
    {
        $this->currentAccount += $product->getPrice() * $quantity;
    }
}
<?php

namespace PyramidSelling\Interfaces;

///// interfaces for sale and profit /////
interface Sale
{
    public function sellProductOrServices($product, $quantity);
}
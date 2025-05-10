<?php

namespace PyramidSelling\StoreService;

use PyramidSelling\Util\NamePrice;

class Product
{
    use NamePrice;
    protected int $barcode;
    public function getBarcode(): int
    {
        return $this->barcode;
    }

    /**
     * @throws PositiveNumberException
     * @throws LenghtNumberException
     */
    public function setBarcode($barcode): void
    {
        if (!is_numeric($barcode)) {
            throw new PositiveNumberException("Barcode must be a numeric value.");
        }
        if ($barcode <= 0) {
            throw new PositiveNumberException("Barcode must be a number greater than zero.");
        }
        if (strlen($barcode) !== 6) {
            throw new LenghtNumberException("Barcode must have exactly 6 characters.");
        }
        $this->barcode = $barcode;
    }
    /**
     * @throws PositiveNumberException
     * @throws LenghtNumberException
     */
    public function __construct($name, $price, $barcode){
        $this->setName($name);
        $this->setPrice($price);
        $this->setBarcode($barcode);
    }
}
<?php

namespace PyramidSelling\StoreService;

use PyramidSelling\Util\NamePrice;

class Services
{
    use NamePrice;

    /**
     * @var int
     */
    protected int $duration;
    public function getDuration(): int
    {
        return $this->duration;
    }
    /**
     * @throws PositiveNumberException
     */
    public function setDuration($duration): void
    {
        if (!is_numeric($duration)) {
            throw new PositiveNumberException("Duration must be a numeric value.");
        }

        if ($duration <= 0) {
            throw new PositiveNumberException("Duration must be a number greater than zero.");
        }
        $this->duration = $duration;
    }

    /**
     * @throws PositiveNumberException
     */
    public function __construct($name, $price, $duration){
        $this->setName($name);
        $this->setPrice($price);
        $this->setDuration($duration);
    }
}
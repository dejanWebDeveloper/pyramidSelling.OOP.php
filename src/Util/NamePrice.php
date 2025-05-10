<?php
namespace PyramidSelling\Util;
trait NamePrice
{
    protected string $name;
    protected int $price;

    public function getName(): string
    {
        return $this->name;
    }
    public function setName($name): void
    {
        $this->name = $name;
    }
    public function getPrice(): int
    {
        return $this->price;
    }
    public function setPrice($price): void
    {
        $this->price = $price;
    }
}
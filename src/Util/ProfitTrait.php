<?php
namespace PyramidSelling\Util;
trait ProfitTrait
{
    protected array $myEmployee = [];

    public function getMyEmployee(): array
    {
        return $this->myEmployee;
    }
}
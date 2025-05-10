<?php

namespace PyramidSelling\Employees;
use PyramidSelling\Interfaces\Sale;
use PyramidSelling\Util\SaleTrait;
///// classes for subseller /////
class SellerSubordinate extends Employee implements Sale
{
    use SaleTrait;

}
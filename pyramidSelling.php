<?php


use PyramidSelling\Application;
use PyramidSelling\Employees\CountEmployeeException;
use PyramidSelling\Employees\MailEmployeeException;
use PyramidSelling\StoreService\LenghtNumberException;
use PyramidSelling\StoreService\PositiveNumberException;

require_once "vendor/autoload.php";

try {
    Application::start();
} catch (CountEmployeeException|MailEmployeeException|PositiveNumberException|LenghtNumberException $e) {
echo $e->getMessage();
}
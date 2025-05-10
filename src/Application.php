<?php
namespace PyramidSelling;
use Exception;
use PyramidSelling\Util\NamePrice;
use PyramidSelling\StoreService\Product;
use PyramidSelling\StoreService\Services;
use PyramidSelling\StoreService\PositiveNumberException;
use PyramidSelling\StoreService\LenghtNumberException;
use PyramidSelling\Employees\Employee;
use PyramidSelling\Employees\MailEmployeeException;
use PyramidSelling\Employees\CountEmployeeException;
use PyramidSelling\Interfaces\Sale;
use PyramidSelling\Util\SaleTrait;
use PyramidSelling\Employees\SellerSubordinate;
use PyramidSelling\Interfaces\Profit;
use PyramidSelling\Util\ProfitTrait;
use PyramidSelling\Employees\SellerMaster;
use PyramidSelling\Employees\Manager;
use PyramidSelling\Employees\Director;
class Application
{
    /**
     * @throws LenghtNumberException
     * @throws MailEmployeeException
     * @throws PositiveNumberException
     * @throws CountEmployeeException
     */
    public static function start(): void
{
/// Creating product and Services ///
$mobilePhone01 = new Product(name: "Samsung S10", price: 450, barcode: 123456);
$mobilePhone02 = new Product("Samsung S11", 420, 456123);
$mobilePhone03 = new Product("Samsung S12", 490, 789456);
$bag01 = new Product("Fenix", 100, 147852);
$bag02 = new Product("Addidas", 120, 723145);
$bag03 = new Product("Milano", 70, 481526);
$lapTop01 = new Product("Asus", 550, 987258);
$lapTop02 = new Product("HP", 300, 254136);
$beautySalon01 = new Services("Haircut", 12, 30);
$beautySalon02 = new Services("Shaving", 10, 15);
$beautySalon03 = new Services("Blow-drying", 18, 45);
$beautySalon04 = new Services("Massage", 50, 50);
/// SubordinateSellers ///
try{
    $andjelaSub01 = new SellerSubordinate("andjela01@gmail.com");
}
catch (Exception $e) {
    echo $e->getMessage();
}
$andjelaSub02 = new SellerSubordinate("andjela02@gmail.com");
$andjelaSub03 = new SellerSubordinate("andjela03@gmail.com");
$andjelaSub04 = new SellerSubordinate("andjela04@gmail.com");
$andjelaSub05 = new SellerSubordinate("andjela05@gmail.com");
$andjelaSub06 = new SellerSubordinate("andjela06@gmail.com");
$andjelaSub07 = new SellerSubordinate("andjela07@gmail.com");
$andjelaSub08 = new SellerSubordinate("andjela08@gmail.com");
/// MasterSellers ///
$markoMaster01 = new SellerMaster("marko01@gamil.com");
$markoMaster02 = new SellerMaster("marko02@gamil.com");
$markoMaster03 = new SellerMaster("marko03@gamil.com");
$markoMaster04 = new SellerMaster("marko04@gamil.com");
$andjelaSub01->sellProductOrServices($bag01, 3);
$andjelaSub02->sellProductOrServices($bag02, 3);
$andjelaSub03->sellProductOrServices($bag03, 3);
$andjelaSub04->sellProductOrServices($lapTop01, 1);
$andjelaSub05->sellProductOrServices($lapTop02, 5);
$andjelaSub06->sellProductOrServices($mobilePhone01, 2);
$andjelaSub07->sellProductOrServices($mobilePhone02, 2);
$andjelaSub08->sellProductOrServices($mobilePhone03, 2);
$markoMaster01->sellProductOrServices($beautySalon01, 5);
$markoMaster02->sellProductOrServices($beautySalon02, 7);
$markoMaster03->sellProductOrServices($beautySalon03, 8);
$markoMaster04->sellProductOrServices($beautySalon04, 2);
/// Managers ///
$nemanjaMng01 = new Manager("nemanja01@gmail.com");
$nemanjaMng02 = new Manager("nemanja02@gmail.com");
/// DIRECTOR ///
$dejanDirector = new Director("dejan01@gmial.com");
/// Adding employee ///
$markoMaster01->addEmployee($andjelaSub01)->addEmployee($andjelaSub02);
$markoMaster02->addEmployee($andjelaSub03)->addEmployee($andjelaSub04);
$markoMaster03->addEmployee($andjelaSub05)->addEmployee($andjelaSub06);
$markoMaster04->addEmployee($andjelaSub07)->addEmployee($andjelaSub08);
$nemanjaMng01->addEmployee($markoMaster01)->addEmployee($markoMaster02);
$nemanjaMng02->addEmployee($markoMaster03)->addEmployee($markoMaster04);
$dejanDirector->addEmployee($nemanjaMng01)->addEmployee($nemanjaMng02);
$dejanDirector->collectProfit();
?>
<pre>
<?php
var_dump($dejanDirector);
?>
</pre>
<?php
}
}

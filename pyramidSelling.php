<?php
///// model class /////
/**
 * model class
 */
abstract class Employee
{
    protected string $email;
    /**
     *
     * @var int
     */
    protected int $currentAccount = 0;
    protected int $savingsAccount = 0;
    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getCurrentAccount(): int
    {
        return $this->currentAccount;
    }
    /**
     * @return int
     */
    public function getSavingsAccount(): int
    {
        return $this->savingsAccount;
    }
    public function setEmail($email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception("Invalid email format");
        }
    }
    public function setCurrentAccount($currentAccount): void
    {
        if (is_numeric($currentAccount) && $currentAccount >= 0) {
            $this->currentAccount = $currentAccount;
        } else {
            throw new Exception("Invalid current account balance");
        }
    }
    public function setSavingsAccount($savingsAccount): void
    {
        if (is_numeric($savingsAccount) && $savingsAccount >= 0) {
            $this->savingsAccount = $savingsAccount;
        } else {
            throw new Exception("Invalid savings account balance");
        }
    }
    public function displaySavingsAccount(): void
    {
        echo "The savings account balance is: ". $this->savingsAccount. "$ <br>";
    }
    public function __construct($email, $currentAccount = 0, $savingsAccount = 0)
    {
        $this->setEmail($email);
    }
}
///// interfaces for sale and profit /////
interface Sale
{
    public function sellProductOrServices($product, $quantity);
}
interface Profit
{
    public function collectProfit();
}
///// traits for sale and profit /////
trait SaleTrait
{
    public function sellProductOrServices($product, $quantity): void
    {
        $this->currentAccount += $product->getPrice() * $quantity;
    }
}
trait ProfitTrait
{
    protected array $myEmployee = [];

    public function getMyEmployee(): array
    {
        return $this->myEmployee;
    }
}
///// classes for subseller /////
class SellerSubordinate extends Employee implements Sale
{
    use SaleTrait;
    
}
///// master seller class /////
class SellerMaster extends Employee implements Sale, Profit
{
    use SaleTrait, ProfitTrait;

    public function addEmployee(Employee&Sale $newEmployee): static
    {
        foreach ($this->myEmployee as $employee) {
            if ($employee->getEmail() === $newEmployee->getEmail()) {
                throw new MailEmployeeException("Person is already an employee in our company.". $newEmployee->getEmail());
            }
        }
        if (count($this->myEmployee) >= 3) {
            throw new CountEmployeeException("You can only have 3 subordinates.");
        }
        if (!$newEmployee instanceof SellerSubordinate) {
            throw new Exception("You can only add subordinates.");
        }
        $this->myEmployee[] = $newEmployee;
        return $this;
    }
    public function collectProfit()
    {
        foreach ($this->getMyEmployee() as $employee) {
            $profit = $employee->getCurrentAccount();
            if ($profit >= 10000) {
                $this->currentAccount += $profit * 0.55;
                $employee->setSavingsAccount($employee->getSavingsAccount() + ($profit * 0.45));    
            }else{
            $this->currentAccount += $profit * 0.6;
            $employee->setSavingsAccount($employee->getSavingsAccount() + ($profit * 0.4));
        }
            $employee->setCurrentAccount(0);
        }
    }
}
class Manager extends SellerMaster
{
    public function addEmployee(Sale&Employee $newEmployee): static
    {
        foreach ($this->myEmployee as $employee) {
            if ($employee->getEmail() === $newEmployee->getEmail()) {
                throw new MailEmployeeException("Person is already an employee in our company.". $newEmployee->getEmail() ."");
            }
        }
        if (count($this->myEmployee) >= 4) {
            throw new CountEmployeeException("You can only have 4 SellerMaster.");
        }
        if (!$newEmployee instanceof SellerMaster) {
            throw new Exception("You can only add SellerMaster.");
        }
        $this->myEmployee[] = $newEmployee;
        return $this;
    }
    public function collectProfit()
    {
        foreach ($this->getMyEmployee() as $employee) {
            $employee->collectProfit();
            $profit = $employee->getCurrentAccount();
            if ($profit >= 10000) {
                $this->currentAccount += $profit * 0.75;
                $employee->setSavingsAccount($employee->getSavingsAccount() + ($profit * 0.25));
            }else{
            $this->currentAccount += $profit * 0.8;
            $employee->setSavingsAccount($employee->getSavingsAccount() + ($profit * 0.2));
        }
        $employee->setCurrentAccount(0);
    }
    }
}

class Director extends Manager
{
    public function addEmployee(Sale&Employee $newEmployee): static
    {
        foreach ($this->myEmployee as $employee) {
            if ($employee->getEmail() === $newEmployee->getEmail()) {
                throw new MailEmployeeException("Person is already an employee in our company." . $newEmployee->getEmail() . "");
            }
        }
        if (count($this->myEmployee) >= 2) {
            throw new Exception("You can only have 2 Managers.");
        }
        if (!$newEmployee instanceof Manager) {
            throw new CountEmployeeException("You can only add Manager.");
        }
        $this->myEmployee[] = $newEmployee;
        return $this;
    }
    public function collectProfit()
    {
        foreach ($this->getMyEmployee() as $employee) {
            $employee->collectProfit();
            $profit = $employee->getCurrentAccount();
            if ($profit >= 20000) {
                $this->currentAccount += $profit * 0.83;
                $employee->setSavingsAccount($employee->getSavingsAccount() + ($profit * 0.17));
            }else{
            $this->currentAccount += $profit * 0.9;
            $employee->setSavingsAccount($employee->getSavingsAccount() + ($profit * 0.1));
            
        }
        $employee->setCurrentAccount(0);
    }
}
}
class MailEmployeeException extends Exception
{
}
class CountEmployeeException extends Exception
{
}
trait NamePrice
{
    protected string $name;
    protected int $price;

    public function getName(){
        return $this->name;
    }
    public function setName($name){
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
class PositiveNumberException extends Exception
{

}
class LenghtNumberException extends Exception
{

}
class Services
{
    use NamePrice;
    protected $duration;
    public function getDuration()
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

/////////////// TESTING ////////////////

/// Creating product and Services ///
$mobilePhone01 = new Product("Samsung S10", 450, 123456);
$mobilePhone02 = new Product("Samsung S11", 420, 456123);
$mobilePhone03 = new Product("Samsung S12", 490, 789456);
$bag01 = new Product("Fenix", 100, 147852);
$bag02 = new Product("Addidas", 120, 723145);
$bag03 = new Product("Milano", 70, 481526);
$lapTop01 = new Product("Asus", 550, 987258);
$lapTop02 = new Product("HP", 300, 254136);

try {
    $beautySalon01 = new Services("Haircut", 12, 30);
}
catch (PositiveNumberException $e) {
    echo $e->getMessage();
}
try {
    $beautySalon01 = new Services("Haircut", 12, 30);
}
catch (PositiveNumberException $e) {
    echo $e->getMessage();
}
try {
    $beautySalon02 = new Services("Shaving", 10, 15);
}
catch (PositiveNumberException $e) {
    echo $e->getMessage();
}
try {
    $beautySalon03 = new Services("Blow-drying", 18, 45);
}
catch (PositiveNumberException $e) {
    echo $e->getMessage();
}
try {
    $beautySalon04 = new Services("Massage", 50, 50);
}
catch (PositiveNumberException $e) {
    echo $e->getMessage();
}
/// SubordinateSellers ///
$andjelaSub01 = new SellerSubordinate("andjela01@gmail.com");
$andjelaSub01->sellProductOrServices($bag01, 3);
$andjelaSub02 = new SellerSubordinate("andjela02@gmail.com");
$andjelaSub02->sellProductOrServices($bag02, 3);
$andjelaSub03 = new SellerSubordinate("andjela03@gmail.com");
$andjelaSub03->sellProductOrServices($bag03, 3);
$andjelaSub04 = new SellerSubordinate("andjela04@gmail.com");
$andjelaSub04->sellProductOrServices($lapTop01, 1);
$andjelaSub05 = new SellerSubordinate("andjela05@gmail.com");
$andjelaSub05->sellProductOrServices($lapTop02, 5);
$andjelaSub06 = new SellerSubordinate("andjela06@gmail.com");
$andjelaSub06->sellProductOrServices($mobilePhone01, 2);
$andjelaSub07 = new SellerSubordinate("andjela07@gmail.com");
$andjelaSub07->sellProductOrServices($mobilePhone02, 2);
$andjelaSub08 = new SellerSubordinate("andjela08@gmail.com");
$andjelaSub08->sellProductOrServices($mobilePhone03, 2);

/// MasterSellers ///
$markoMaster01 = new SellerMaster("marko01@gamil.com");

$markoMaster01->sellProductOrServices($beautySalon01, 5);
$markoMaster02 = new SellerMaster("marko02@gamil.com");
$markoMaster02->sellProductOrServices($beautySalon02, 7);
$markoMaster03 = new SellerMaster("marko03@gamil.com");
$markoMaster03->sellProductOrServices($beautySalon03, 8);
$markoMaster04 = new SellerMaster("marko04@gamil.com");
$markoMaster04->sellProductOrServices($beautySalon04, 2);

/// Managers ///
$nemanjaMng01 = new Manager("nemanja01@gmail.com");
$nemanjaMng02 = new Manager("nemanja02@gmail.com");

/// DIRECTOR ///
$dejanDirector = new Director("dejan01@gmial.com");

/// Adding employee ///

try {
    $markoMaster01->addEmployee($andjelaSub01)->addEmployee($andjelaSub02);
} catch (CountEmployeeException $e) {

} catch (MailEmployeeException $e) {

} catch (Exception $e) {
}
try {
    $markoMaster02->addEmployee($andjelaSub03)->addEmployee($andjelaSub04);
} catch (CountEmployeeException $e) {

} catch (MailEmployeeException $e) {

} catch (Exception $e) {
}
try {
    $markoMaster03->addEmployee($andjelaSub05)->addEmployee($andjelaSub06);
} catch (CountEmployeeException $e) {

} catch (MailEmployeeException $e) {

} catch (Exception $e) {
}
try {
    $markoMaster04->addEmployee($andjelaSub07)->addEmployee($andjelaSub08);
} catch (CountEmployeeException $e) {

} catch (MailEmployeeException $e) {

} catch (Exception $e) {
}

try {
    $nemanjaMng01->addEmployee($markoMaster01)->addEmployee($markoMaster02);
} catch (CountEmployeeException $e) {

} catch (MailEmployeeException $e) {

} catch (Exception $e) {
}
try {
    $nemanjaMng02->addEmployee($markoMaster03)->addEmployee($markoMaster04);
} catch (CountEmployeeException $e) {

} catch (MailEmployeeException $e) {

} catch (Exception $e) {
}
try {
    $dejanDirector->addEmployee($nemanjaMng01)->addEmployee($nemanjaMng02);
} catch (CountEmployeeException $e) {

} catch (MailEmployeeException $e) {

} catch (Exception $e) {
}

$dejanDirector->collectProfit();

?>
<pre>
<?php
var_dump($dejanDirector);
?>
</pre>

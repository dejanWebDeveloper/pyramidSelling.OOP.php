<?php
///// model class /////
abstract class Employee
{
    protected $email;
    protected $currentAccount = 0;
    protected $savingsAccount = 0;

    public function getEmail()
    {
        return $this->email;
    }

    public function getCurrentAccount()
    {
        return $this->currentAccount;
    }

    public function getSavingsAccount()
    {
        return $this->savingsAccount;
    }

    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception("Invalid email format");
        }
    }

    public function setCurrentAccount($currentAccount)
    {
        if (is_numeric($currentAccount) && $currentAccount >= 0) {
            $this->currentAccount = $currentAccount;
        } else {
            throw new Exception("Invalid current account balance");
        }
    }

    public function setSavingsAccount($savingsAccount)
    {
        if (is_numeric($savingsAccount) && $savingsAccount >= 0) {
            $this->savingsAccount = $savingsAccount;
        } else {
            throw new Exception("Invalid savings account balance");
        }
    }
    public function displaySavingsAccount()
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
    public function sellProductOrServices($product, $quantity)
    {
        $this->currentAccount += $product->getPrice() * $quantity;
    }
}
trait ProfitTrait
{
    protected $myEmployee = [];

    public function getMyEmployee()
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

    public function addEmployee(Sale $employee)
    {
        if (count($this->myEmployee) >= 3) {
            throw new Exception("You can only have 3 subordinates.");
        }
        if (!$employee instanceof SellerSubordinate) {
            throw new addEmployeeException("You can only add subordinates.");
        }
        $this->myEmployee[] = $employee;
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
    public function addEmployee(Sale $employee)
    {
        if (count($this->myEmployee) >= 4) {
            throw new Exception("You can only have 4 SellerMaster.");
        }
        if (!$employee instanceof SellerMaster) {
            throw new addEmployeeException("You can only add SellerMaster.");
        }
        $this->myEmployee[] = $employee;
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
    public function addEmployee(Sale $employee)
    {
        if (count($this->myEmployee) >= 2) {
            throw new Exception("You can only have 2 Managers.");
        }
        if (!$employee instanceof Manager) {
            throw new addEmployeeException("You can only add Manager.");
        }
        $this->myEmployee[] = $employee;
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
class addEmployeeException extends Exception
{
   
}

///// test /////

$jovanSub = new SellerSubordinate("jovan@gmail.com");
$jovanSub->setCurrentAccount(10000);
$milosSub = new SellerSubordinate("milos@gmail.com");
$milosSub->setCurrentAccount(1000);
$petarSub = new SellerSubordinate("petar@mail.com");
$petarSub->setCurrentAccount(1000);

$mitarMr = new SellerMaster("mitar@gmail.com");
//$mitarMr->setCurrentAccount(1000);
$markoMr = new SellerMaster("master@gmail.com");
//$markoMr->setCurrentAccount(1000);
$jovicaMr = new SellerMaster("jovica@gmail.com");
//$jovicaMr->setCurrentAccount(1000);
$miljanMr = new SellerMaster("miljan@gmail.com");
//$miljanMr->setCurrentAccount(1000);
$ljuboMr = new SellerMaster("ljubo@gmail.com");
//$ljuboMr->setCurrentAccount(1000);

$djordjeMg = new Manager("djordje@gmail.com");
$milenaMg = new Manager("milena@gmail.com");
$milicaMg = new Manager("milica@gmail.com");
//$djordjeMg->setCurrentAccount(1000);

$nemanjaDr = new Director("nemanja@gmail.com");

try
{
$markoMr->addEmployee($jovanSub)->addEmployee($milosSub)->addEmployee($petarSub);
}
catch (addEmployeeException $e)
{
    echo $e->getMessage();
}
catch (Exception $e)
{
    echo $e->getMessage();

}

try
{
    $djordjeMg->addEmployee($mitarMr)->addEmployee($jovicaMr)->addEmployee($miljanMr)->addEmployee($markoMr);
}
catch (addEmployeeException $e)
{
    echo $e->getMessage();
}
catch (Exception $e)
{
    echo $e->getMessage();
}

try
{
    $nemanjaDr->addEmployee($djordjeMg)->addEmployee($milenaMg);
}
catch (addEmployeeException $e)
{
    echo $e->getMessage();
}
catch (Exception $e)
{
    echo $e->getMessage();
}

//$markoMr->collectProfit();
//$djordjeMg->collectProfit();
$nemanjaDr->collectProfit();
///// osmisli proveru validacije /////

?>
<pre>
<?php
var_dump($nemanjaDr);
?>
</pre>
<?php





?>

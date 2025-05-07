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

trait NamePrice
{
    protected $name;
    protected $price;

    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getPrice(){
        return $this->price;
    }
    public function setPrice($price){
        $this->price = $price;
    }
}

class Product 
{
    use NamePrice;
    protected $barcode;
    public function getBarcode()
    {
        return $this->barcode;
    }
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }
    public function __construct($name, $price, $barcode){
        $this->name = $name;
        $this->price = $price;
        $this->barcode = $barcode;
    }

}

class Services
{
    use NamePrice;
    protected $duration;
    public function getDuration()
    {
        return $this->duration;
    }
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }
    public function __construct($name, $price, $duration){
        $this->setName($name);
        $this->setPrice($price);
        $this->setDuration($duration);
    }
}


?>

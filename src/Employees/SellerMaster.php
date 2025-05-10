<?php

namespace PyramidSelling\Employees;

///// master seller class /////

use Exception;
use PyramidSelling\Interfaces\Profit;
use PyramidSelling\Interfaces\Sale;
use PyramidSelling\Util\ProfitTrait;
use PyramidSelling\Util\SaleTrait;

class SellerMaster extends Employee implements Sale, Profit
{
    use SaleTrait, ProfitTrait;

    /**
     * @throws CountEmployeeException
     * @throws MailEmployeeException
     * @throws Exception
     */
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
    public function collectProfit(): void
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
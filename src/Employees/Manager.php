<?php

namespace PyramidSelling\Employees;

use Exception;
use PyramidSelling\Interfaces\Sale;

class Manager extends SellerMaster
{
    /**
     * @throws CountEmployeeException
     * @throws MailEmployeeException
     * @throws Exception
     */
    public function addEmployee(Sale|Employee $newEmployee): static
    {
        foreach ($this->myEmployee as $employee) {
            if ($employee->getEmail() === $newEmployee->getEmail()) {
                throw new MailEmployeeException(message: "Person is already an employee in our company.". $newEmployee->getEmail());
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
    public function collectProfit(): void
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
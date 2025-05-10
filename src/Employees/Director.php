<?php

namespace PyramidSelling\Employees;

use Exception;
use PyramidSelling\Interfaces\Sale;

class Director extends Manager
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
                throw new MailEmployeeException("Person is already an employee in our company." . $newEmployee->getEmail());
            }
        }
        if (!$newEmployee instanceof Manager) {
            throw new CountEmployeeException("You can only add Manager.");
        }
        if (count($this->myEmployee) >= 2) {
            throw new Exception("You can only have 2 Managers.");
        }
        $this->myEmployee[] = $newEmployee;
        return $this;
    }
    public function collectProfit(): void
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
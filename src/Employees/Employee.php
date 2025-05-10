<?php
namespace PyramidSelling\Employees;

///// model class /////
use Exception;

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

    /**
     * @throws
     */
    public function setEmail($email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception("Invalid email format");
        }
    }

    /**
     * @throws Exception
     */
    public function setCurrentAccount($currentAccount): void
    {
        if (is_numeric($currentAccount) && $currentAccount >= 0) {
            $this->currentAccount = $currentAccount;
        } else {
            throw new Exception("Invalid current account balance");
        }
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    public function __construct($email)
    {
        $this->setEmail($email);
    }
}
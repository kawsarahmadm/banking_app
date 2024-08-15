<?php
namespace App;
use App\FileHandler;

class BalanceManagement
{
    private $customerEmail;
    private $total_deposit = 0;
    private $transferred_amount_fromAnother = 0;
    private $transferred_amount_toAnother = 0;
    private $total_withdraw = 0;
    private $transactions;
    private $customers;

    public function __construct($customerEmail) {
      $this->customerEmail = $customerEmail;
      $fileHandler = new FileHandler("../database/users.json");
      $this->customers = $fileHandler->loadData();
      $transitionFile = new FileHandler("../database/transactions.json");
      $this->transactions = $transitionFile->loadData();
    
    }

    public function getTotalDeposit() : int {
       
        foreach ($this->customers as $customer) {
     
            if ($customer['email'] === $this->customerEmail) {
              foreach ($this->transactions as $transaction) {
               
                if ($customer['email'] ==$transaction['account'] && $transaction['transitionType'] == 'deposit' ) {
                
                     $this->total_deposit += $transaction['amount'];
                }
                
              }
            }
          }
        
        return $this->total_deposit;
    }

    public function getTransferredFromAnother(): int {
        
        foreach ($this->customers as $customer) {
            if ($customer['email'] === $this->customerEmail) {
              foreach ($this->transactions as $transaction) {
                if ($customer['email'] ==$transaction['toAccount'] && $transaction['transitionType'] == 'transfer' ) {
                  $this->transferred_amount_fromAnother += $transaction['amount'];
                  }
              }
            }
          }
          return $this->transferred_amount_fromAnother;
    }

    public function getTransferredToAnother() : int {       
        foreach ($this->customers as $customer) {
            if ($customer['email'] === $this->customerEmail) {
              foreach ($this->transactions as $transaction) {
                if ($customer['email'] ==$transaction['account'] && $transaction['transitionType'] == 'transfer' ) {
                    $this->transferred_amount_toAnother += $transaction['amount'];
                  }
              }
            }
          }
          return $this->transferred_amount_toAnother;
    }

    public function getTotalWithdraw() : int  {
        foreach ($this->customers as $customer) {
            if ($customer['email'] === $this->customerEmail) {
              foreach ($this->transactions as $transaction) {
                if ($customer['email'] == $transaction['account'] && $transaction['transitionType'] == 'withdraw' ) {
                     $this->total_withdraw += $transaction['amount'];
                }
            }
          }
        }
        return  $this->total_withdraw;
    }

    public function getCurrentBalance() : int {
      $totalIncome = $this->getTotalDeposit() + $this->getTransferredFromAnother();
      $totalExpense = $this->getTotalWithdraw() + $this->getTransferredToAnother();
      return   $totalIncome - $totalExpense;
 
    }
}



  
  







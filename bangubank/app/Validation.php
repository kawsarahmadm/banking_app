<?php
namespace App;
use App\Helpers\Sanitize;
class Validation
{
    private $errors = [];


    public function required($field, $value, $error_msg)  
    {
        if (empty($value)) {
            return $this->errors[$field] = $error_msg;
        }
    }
    public function emailValidate($email) 
    {
        if (empty($email)) {
            return $this->addError("email","You can't empty email field!");
        }else{
            $sanitize = new Sanitize;
            $email = $sanitize->sanitize($email);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              return   $this->addError('email',"Please provide a valid email address!");
            }
        }
    }
    public function passwordValidate($password)
    {
        $sanitize = new Sanitize;
        if (empty($password)) {
            $this->errors['password'] = "Please provide a password!";
        } elseif (strlen($_POST['password']) < 4) {
            return $this->addError("password","Please provide a password longer than 4 characters!");
        } else {
            $password = $sanitize->sanitize($_POST['password']);
        }
    }

    public function addError($field, $error)  
    {
        $this->errors[$field] = $error;
    }

    public function getError($field = null)  
    {
        return $this->errors[$field] ?? [];
    }

    public function hasErrors($field = null) : bool 
    {
        if ($field) {
            return !empty($this->errors[$field]);
        }
        return !empty($this->errors);
    }
}

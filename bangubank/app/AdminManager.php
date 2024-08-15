<?php
namespace App;
use App\FileHandler;
use App\Helpers\Sanitize;
class AdminManager
{
    private FileHandler $fileHandler;
    private Sanitize $sanitize;
    private  $validation;
    public function __construct() {

        $this->fileHandler = new FileHandler("database/admins.json");
        $this->sanitize = new Sanitize;
        $this->validation = new Validation;
        
    }
    public function  registerAdmin()  {
        $name = trim(readline("Enter Your Name: "));
        while (empty($name)||(is_numeric($name))) {
            echo "You must give a valid name:\n";
            $name =  trim(readline("Enter Your Name: "));
        }
        $email = trim(readline("Enter Your Email: "));
        while (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "You must give a valid email:\n";
            $email = trim(readline("Enter a valid email: "));
        }

        $admins = $this->fileHandler->loadData();
        $adminEmailFound = false;
        foreach ($admins as $admin) {
            if ($admin['email'] == $email) {
                $adminEmailFound = true;
                break; 
            }
        }

        while ($adminEmailFound) {
            echo "This email already exists! Please enter a different email:\n";
            $email = trim(readline("Enter a valid email: "));
            while (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "You must give a valid email:\n";
                $email = trim(readline("Enter a valid email: "));
            }
            
            // Check again if the new email already exists
            $adminEmailFound = false;
            foreach ($admins as $admin) {
                if ($admin['email'] == $email) {
                    $adminEmailFound = true;
                    break;  // Exit loop once we find a matching email
                }
            }
        }

        $password = trim(readline("Enter Your password: "));
        while (empty($password)||(strlen($password) < 4)) {
            echo "Please provide a password longer than 4 characters!:\n";
            $password =  trim(readline("Enter a valid password: "));
        }

        $userData = array(
            "name"=> $name,
            "email"=> $email,
            "password"=> password_hash($password,PASSWORD_BCRYPT),
        );
        $this->fileHandler->saveData($userData);
        echo "registration is successful";
        exit;
    }

    public function loginAdmin($email, $password) 
    {
        $admins = $this->fileHandler->loadData();
        foreach ($admins as $admin) {
            if ($admin['email'] == $email && password_verify($password, $admin['password'])) {
                return true;
            }
        }
        return  $this->validation->addError('auth_error',"Email or Password is not valid");
    }
}


        
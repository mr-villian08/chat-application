<?php

class Validations
{

    private $errors = array();

    // ? ********************************************************** Register validations ********************************************************** */
    public function registerValidation($name, $username, $email, $password, $confirmPassword)
    {
        if (strlen($name) < 2 || strlen($name) > 30) {
            $this->errors = array_merge($this->errors, ["name" => 'Name must be between 2 to 30 characters!']);
        }

        if (strlen($username) < 2 || strlen($username) > 8) {
            $this->errors =  array_merge($this->errors, ["username" => 'User Name must be between 2 to 8 characters!']);
        }

        $query = Database::query("SELECT * FROM users WHERE username = '$username'");
        if ($query->num_rows > 0) {
            $this->errors =  array_merge($this->errors, ["username" => 'User Name already exists!']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors =  array_merge($this->errors, ["email" => 'Enter a valid email!']);
        }

        $query = Database::query("SELECT * FROM users WHERE email = '$email'");
        if ($query->num_rows > 0) {
            $this->errors =  array_merge($this->errors, ["email" => 'Email already exists!']);
        }


        if ($password != $confirmPassword) {
            $this->errors =  array_merge($this->errors, ["password" => 'Password and confirm password are not same!']);
        }



        return $this->errors;
    }

    // ? ************************************************************** Get Error ************************************************************ */
    public function getError($fieldName)
    {
        if (in_array($fieldName, $this->errors)) {
            return $this->errors[$fieldName];
        }

        return "";
    }
}

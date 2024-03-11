<?php

class AuthController
{
    private $errors = array();
    public $connection;

    public function __construct($connection)
    {
        $this->$connection = $connection;
    }


    // ? ********************************************************** Register the user ********************************************************** */
    public function register($name, $username, $email, $password, $confirmPassword): bool
    {
        Database::connect();
        $errors = $this->registerValidation($name, $username, $email, $password, $confirmPassword);
        if (count($errors) != 0) {
            return false;
        }

        $password = hash("sha512", $password);
        $register =  Database::query("INSERT INTO users (name, username, email, password, created_at, updated_at) VALUES ('$name', '$username', '$email', '$password', NOW(), NOW())");

        if ($register) {
            Database::disconnect();
            return true;
        }

        Database::disconnect();
        return false;
    }

    // ? ********************************************************** Login the user ********************************************************** */
    public function login($email, $password): array
    {
        Database::connect();
        $errors = $this->loginValidation($email, $password);
        if (count($errors) != 0) {
            return ['status' => false];
        }

        $login = Database::query("SELECT * FROM users WHERE email = '$email'");

        if ($login->num_rows > 0) {
            // update the login status
            Database::query("UPDATE users SET is_login = 1 WHERE email = '$email'");
            Database::disconnect();
            return ["status" => true, 'data' => $login->fetch_object()];
        }

        Database::disconnect();
        return ['status' => false];
    }

    // ? ********************************************************** Register validations ********************************************************** */
    public function registerValidation($name, $username, $email, $password, $confirmPassword): array
    {
        if (empty($name) || strlen($name) < 2 || strlen($name) > 30) {
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
    // ? ********************************************************** Login validations ********************************************************** */
    public function loginValidation($email, $password): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors =  array_merge($this->errors, ["email" => 'Enter a valid email!']);
        }

        $query = Database::query("SELECT * FROM users WHERE email = '$email'");
        if ($query->num_rows === 0) {
            return $this->errors =  array_merge($this->errors, ["login" => 'Email does not exists!']);
        }

        $data = $query->fetch_assoc();
        if (empty($password)) {
            $this->errors =  array_merge($this->errors, ["login" => 'Wrong username or password!']);
        }

        if (hash("sha512", $password) != $data['password']) {
            $this->errors =  array_merge($this->errors, ["login" => 'Wrong username or password!']);
        }

        return $this->errors;
    }

    // ? ************************************************************** Log Out ************************************************************ */
    public function logOut()
    {
        if (isset($_POST['logout'])) {
            $email = $_SESSION['userLoggedIn']['email'];
            Database::connect();
            $result = Database::query("UPDATE users SET is_login = 0 WHERE email = '$email'");
            if ($result->num_rows === 0) {
                return $this->errors =  array_merge($this->errors, ["logout" => 'Unable to logout. Try again!']);
            }
            
            Database::disconnect();
            session_destroy();
            return header('location: ./auth/register.php');
        }

        Database::disconnect();
        return false;
    }

    // ? ************************************************************** Get Error ************************************************************ */
    public function getError($fieldName): string
    {
        if (array_key_exists($fieldName, $this->errors)) {
            $error = $this->errors[$fieldName];
            return "<div class='alert alert-danger mb-2 mt-4' role='alert'> $error </div>";
        }

        return "";
    }
}

<?php

class AccessorsAndMutators
{
    // ? ******************************************************* Set name ******************************************************* */
    static function setName($name): string
    {
        $name = strip_tags($name);
        $name = str_replace(" ", "", $name);
        $name = strtolower($name);
        $name = ucfirst($name);
        return $name;
    }

    // ? ******************************************************* Set Username ******************************************************* */
    public static function setUsername($username): string
    {
        $username = strip_tags($username);
        $username = str_replace(" ", "", $username);
        $username = strtolower($username);
        return $username;
    }

    // ? ******************************************************* Set Email ******************************************************* */
    public static function setEmail($email): string
    {
        $email = strip_tags($email);
        $email = str_replace(" ", "", $email);
        $email = strtolower($email);
        return $email;
    }

    // ? ******************************************************* Set Password ******************************************************* */
    public static function setPassword($password): string
    {
        $password = strip_tags($password);
        return $password;
    }
}

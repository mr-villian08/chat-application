<?php

class UserController
{
    public $connection;

    public function __construct($connection)
    {
        $this->$connection = $connection;
    }

    // ? *************************************************************** Show all the users *************************************************************** */
    public function show(): array
    {
        $query = Database::query("SELECT * FROM userS");

        return $query->fetch_all(MYSQLI_ASSOC);
    }
}

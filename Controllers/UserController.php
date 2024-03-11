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
        $query = Database::query("SELECT * FROM users");

        return $query->fetch_all(MYSQLI_ASSOC);
    }

    // ? *************************************************************** Find the users *************************************************************** */
    public function find($id)
    {
        $query = Database::query("SELECT * FROM users where id = $id");

        return $query->fetch_object();
    }
}

<?php

class ChatController
{
    public $connection;
    protected $clients;

    public function __construct($connection)
    {
        $this->$connection = $connection;
        $this->clients = new \SplObjectStorage;
    }

    // ? ************************************************************* Save the messages ************************************************************* */
    public function save($message, $userId)
    {
        $query = Database::query("INSERT INTO chats (messages, user_id) VALUES ($message, $userId, Now(), Now())");
        if ($query) {
            Database::disconnect();
            return true;
        }

        return false;
    }

    // ? ************************************************************* Show the chats ************************************************************* */
    public function show()
    {
        $query = Database::query("SELECT * FROM chats INNER JOIN users ON users.id = chats.user_id ORDER BY chats.created_at DESC");
        return $query->fetch_assoc();
    }
}

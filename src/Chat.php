<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . '../config/DB.php';
require dirname(__DIR__) . '../Controllers/UserController.php';

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ? '' : 's'
        );

        $data = json_decode($msg, true);
        \Database::connect();
        $message = $data['message'];
        $userId = $data['user_id'];
        \Database::query("INSERT INTO chats (message, user_id, created_at, updated_at) VALUES ('$message', $userId, NOW(), NOW())");
        $user = new \UserController(\Database::connect());
        $userData = $user->find($data['user_id']);
        $username = $userData->username;
        $created_at = date("d-m-Y h:i:s");

        foreach ($this->clients as $client) {

            if ($from == $client) {
                $data['from'] = 'Me';
                $data['created_at'] = $created_at;
            } else {
                $data['created_at'] = $created_at;
                $data['from'] = $username;
            }

            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

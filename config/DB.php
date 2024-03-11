<?php
ob_start();
session_start();

class Database
{

    private static $host = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $database = "chat-application";
    private static $connection;

    public static function connect()
    {
        self::$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$database);
        if (!self::$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public static function query($sql)
    {
        return mysqli_query(self::$connection, $sql);
    }

    public static function disconnect()
    {
        mysqli_close(self::$connection);
    }
}

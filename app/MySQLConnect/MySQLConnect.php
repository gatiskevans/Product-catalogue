<?php

namespace App\MySQLConnect;

use PDO;

abstract class MySQLConnect
{
    protected function connect(): PDO
    {
        $json = json_decode(file_get_contents('config.json'), true);

        $host = $json['host'];
        $user = $json['user'];
        $password = $json['password'];
        $database = $json['database'];

        $dsn = 'mysql:host=' . $host . ';dbname=' . $database;
        return new PDO($dsn, $user, $password);
    }
}
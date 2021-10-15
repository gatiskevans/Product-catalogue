<?php

namespace App\MySQLConnect;

use PDO;

class MySQLConnect
{
    private string $host = '127.0.0.1';
    private string $user = 'root';
    private string $password = 'root';
    private string $database = 'product_catalogue';

    protected function connect(): PDO
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->database;
        return new PDO($dsn, $this->user, $this->password);
    }
}
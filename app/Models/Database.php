<?php

namespace App\Models;

use Dotenv\Dotenv;
use mysqli;

class Database
{
    private $connection;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->connection = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
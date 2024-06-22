<?php

namespace App\Models;

require __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use mysqli;

class Database
{
    public function getConnection()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');

        $connection = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        return $connection;
    }
}
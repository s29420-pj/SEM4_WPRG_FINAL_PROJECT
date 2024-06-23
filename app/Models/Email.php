<?php

namespace App\Models;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

class Email
{
    public function sendEmail($email, $subject, $message): void
    {
        $connection = (new Database())->getConnection();
        $query = "INSERT INTO wprg_contact (email, subject, message) VALUES ('$email', '$subject', '$message')";
        $connection->query($query);
        $connection->close();
    }

    public function getEmails(): array
    {
        $user = new User();
        if (!$user->isLoggedIn() || ($user->getUserRole($user->getUserID()) !== 'ADMIN' && $user->getUserRole($user->getUserID()) !== 'AUTHOR')) {
            throw new Exception('You must be logged in to view emails and you have to be an admin or author to view emails');
        }

        $connection = (new Database())->getConnection();
        $query = 'SELECT * FROM wprg_contact';
        $result = $connection->query($query);
        $emails = $result->fetch_all(MYSQLI_ASSOC);
        $connection->close();

        return $emails;
    }
}
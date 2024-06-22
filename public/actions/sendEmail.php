<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Controllers\EmailController;

$emailController = new EmailController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if (empty($email) || empty($subject) || empty($message)) {
        header("Location: ../index.php?error=empty fields");
        exit();
    }

    $emailController->sendEmail($email, $subject, $message);

    header("Location: ../index.php?success=email sent");
} else {
    header("Location: ../index.php");
}
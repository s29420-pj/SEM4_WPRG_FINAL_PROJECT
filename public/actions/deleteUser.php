<?php

use App\Controllers\UserController;

require_once '../../vendor/autoload.php';

// Check if the comment ID is provided
if (!isset($_GET['id'])) {
    header("Location: ../admin.php");
    exit();
}

$userID = $_GET['id'];

$userController = new UserController();
$userController->deleteUser($userID);

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
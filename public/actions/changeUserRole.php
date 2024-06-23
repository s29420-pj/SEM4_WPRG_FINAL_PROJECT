<?php

require_once '../../vendor/autoload.php';

use App\Controllers\UserController;
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['id'];
    $newRole = $_POST['role'];

    // Get the ID of the currently logged-in user
    $loggedInUserID = $userController->getUserID();

    // Check if the user is logged in and is an admin
    if ($userController->isLoggedIn() && $userController->getUserRole($loggedInUserID) === 'ADMIN') {
        $userController->updateUserRole($userID, $newRole);
        header('Location: ../admin.php');
    } else {
        // Redirect to an error page or display an error message
        header('Location: ../error.php');
    }
}
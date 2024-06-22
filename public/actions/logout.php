<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\UserController;

$userController = new UserController();
$userController->logout();

header('Location: ../index.php');
exit();
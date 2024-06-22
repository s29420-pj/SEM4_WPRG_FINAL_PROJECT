<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;

$user = new User();
$user->logout();

header('Location: index.php');
exit();
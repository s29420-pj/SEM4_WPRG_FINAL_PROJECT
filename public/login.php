<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userController = new UserController();

    if ($userController->login($username, $password)) {
        // Użytkownik jest zalogowany, przekieruj go do strony index.php
        header('Location: index.php');
        exit;
    } else {
        // Logowanie nie powiodło się, wyświetl błąd
        $error = "Nieprawidłowa nazwa użytkownika lub hasło";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+NG+Modern:wght@100..400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<style>
    .logo {
        font-family: 'Playwrite NG Modern', sans-serif;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }
</style>
<body class="d-flex flex-column vh-100">
<header class="bg-light py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="index.php">
                    <img src="./img/logo.png" alt="Logo" class="img-fluid" style="height: 120px; width: auto">
                </a>
            </div>
            <h1 class="text-center flex-grow-1 mb-0 logo">Szlakiem Przygód</h1>
        </div>
    </div>
</header>
<div class="container flex-row">
    <div class="row justify-content-center align-self-center">
        <div class="col-md-6">
            <h2 class="text-center">Logowanie</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Hasło</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Zaloguj się</button>
            </form>
        </div>
    </div>
</div>
<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>Indeks: s29420</p>
    </div>
</footer>
<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>

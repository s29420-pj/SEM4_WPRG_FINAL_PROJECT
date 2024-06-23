<?php
require_once '../../vendor/autoload.php';

use App\Controllers\UserController;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['password']) && isset($_POST['confirmPassword'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password === $confirmPassword) {
            $user = new UserController();

            try {
                $user->resetPassword($_SESSION['id'], $password);
            } catch (Exception $e) {
                $_SESSION['error'] = 'Wystąpił błąd podczas aktualizacji hasła.';
                header('Location: ../profile.php');
                exit();
            }

            $_SESSION['message'] = 'Hasło zostało zaktualizowane pomyślnie.';
            header('Location: ../profile.php');
            exit();
        } else {
            $_SESSION['error'] = 'Hasła nie są takie same.';
            header('Location: ../profile.php');
            exit();
        }
    }
}

header('Location: ../profile.php');
exit();
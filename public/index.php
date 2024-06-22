<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Controllers\UserController;
use App\Controllers\PostController;
use App\Controllers\CommentController;

$user = new UserController();
$postModel = new PostController();

$posts = $postModel->getPosts();
$loggedIn = $user->isLoggedIn();
$userRole = $loggedIn ? $user->getUserRole($user->getUserID()) : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Szlakiem Przygód</title>
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/bootstrap.rtl.css">
    <link rel="stylesheet" href="./css/bootstrap-grid.css">
    <link rel="stylesheet" href="./css/bootstrap-grid.rtl.css">
    <link rel="stylesheet" href="./css/bootstrap-reboot.css">
    <link rel="stylesheet" href="./css/bootstrap-reboot.rtl.css">
    <link rel="stylesheet" href="./css/bootstrap-utilities.css">
    <link rel="stylesheet" href="./css/bootstrap-utilities.rtl.css">
</head>
<body>
    <header class="d-flex justify-content-between align-items-center px-3 py-2 bg-light">
        <div class="logo">
            <img src="./img/logo.jpg" alt="Logo" class="img-fluid">
        </div>
        <h1 class="text-center flex-grow-1">Szlakiem Przygód</h1>
        <nav>
            <ul class="nav">
                <?php if ($loggedIn): ?>
                    <li class="nav-item"><a href="logout.php" class="nav-link">LogOut</a></li>
                    <?php if ($userRole === 'ADMIN'): ?>
                        <li class="nav-item"><a href="admin.php" class="nav-link">Admin Panel</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                <?php endif; ?>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main class="container py-4">
        <?php foreach ($posts as $post): ?>
            <div class="card mb-4">
                <img src="<?= $post['image_path'] ?>" class="card-img-top" alt="Post Image">
                <div class="card-body">
                    <h2 class="card-title"><?= $post['title'] ?></h2>
                    <p class="card-text"><?= $post['short_content'] ?></p>
                    <p class="card-text"><small class="text-muted">Author: <?= $post['author'] ?></small></p>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <p>Indeks: s29420</p>
        </div>
    </footer>
    <script src="./js/bootstrap.bundle.js"></script>
    <script src="./js/bootstrap.esm.js"></script>
    <script src="./js/bootstrap.js"></script>
</body>
</html>
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\CommentController;
use App\Models\Log;

$postController = new PostController();
$userController = new UserController();
$commentController = new CommentController();
$logger = new Log();

// Fetch the post from the database
$loggedIn = $userController->isLoggedIn();
$userRole = $loggedIn ? $userController->getUserRole($userController->getUserID()) : null;

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
            <nav>
                <ul class="nav">
                    <?php if ($loggedIn): ?>
                        <?php if ($userRole === 'ADMIN' || $userRole === 'AUTHOR'): ?>
                            <li class="nav-item me-2"><a href="createPost.php" class="btn btn-success">Utwórz Post</a></li>
                            <li class="nav-item me-2"><a href="mail.php" class="btn btn-info">Mail</a></li>
                        <?php endif; ?>
                        <?php if ($userRole === 'ADMIN'): ?>
                            <li class="nav-item me-2"><a href="admin.php" class="btn btn-dark">Panel Administratora</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item me-2"><a href="login.php" class="btn btn-light">Zaloguj</a></li>
                        <li class="nav-item me-2"><a href="register.php" class="btn btn-dark">Zarejestruj</a></li>
                    <?php endif; ?>
                    <?php if ($userRole !== 'AUTHOR' && $userRole !== 'ADMIN'): ?>
                        <li class="nav-item me-1"><a href="contact.php" class="btn btn-light">Kontakt</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="index.php" class="btn btn-light">Home</a></li>
                    <?php if ($loggedIn): ?>
                        <li class="nav-item me-1"><a href="profile.php" class="btn btn-light">Profil</a></li>
                        <li class="nav-item me-1"><a href="actions/logout.php" class="btn btn-danger">Wyloguj</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Posty</h2>
            <?php foreach ($postController->getPosts() as $post): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title"><?= $post['title'] ?></h5>
                                <p class="card-text"><small class="text-muted">Author: <?= $userController->getUsernameByID($post['wprg_users_id'])['username'] ?></small></p>
                            </div>
                            <a href="actions/deletePost.php?id=<?= $post['id'] ?>" class="btn btn-danger">Usuń</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <h2>Komentarze</h2>
            <?php foreach ($commentController->getComments() as $comment): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title
                    <?php if ($comment['wprg_users_id'] === $userController->getUserID()): ?>
                        text-primary
                    <?php endif; ?>
                    ">
                                    <?= $comment['content'] ?>
                                </h5>
                                <p class="card-text"><small class="text-muted">Author: <?= $userController->getUsernameByID($comment['wprg_users_id'])['username'] ?></small></p>
                            </div>
                            <a href="actions/deleteComment.php?id=<?= $comment['id'] ?>" class="btn btn-danger">Usuń</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <h2>Użytkownicy</h2>
            <?php foreach ($userController->getUsers() as $user): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title"><?= $user['username'] ?></h5>
                                <p class="card-text"><small class="text-muted">Role: <?= $user['role'] ?></small></p>
                            </div>
                            <div class="d-flex align-items-center">
                                <form action="actions/changeUserRole.php" method="post" class="d-inline-block me-2">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <select name="role" class="form-select d-inline-block" style="width: auto;">
                                        <option value="ADMIN" <?= $user['role'] === 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
                                        <option value="AUTHOR" <?= $user['role'] === 'AUTHOR' ? 'selected' : '' ?>>AUTHOR</option>
                                        <option value="USER" <?= $user['role'] === 'USER' ? 'selected' : '' ?>>USER</option>
                                    </select>
                                    <button type="submit" class="btn btn-success">Zmień rolę</button>
                                </form>
                                <a href="actions/deleteUser.php?id=<?= $user['id'] ?>" class="btn btn-danger">Usuń</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <h2>Logi</h2>
            <div class="card">
                <div class="card-body overflow-scroll" style="height: 32rem; overflow-y: auto;">
                    <?php foreach ($logger->getLogs() as $log): ?>
                        <p class=""><?= $log['timestamp']?>  ::  <?= $userController->getUsernameByID($log['user_id'])['username'] ?>  ::  <?= $log['action'] ?></p>
                    <?php endforeach; ?>
                </div>
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
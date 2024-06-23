<?php
require_once '../vendor/autoload.php';

use App\Controllers\PostController;
use App\Controllers\UserController;

$userController = new UserController();
$loggedIn = $userController->isLoggedIn();
$userRole = $loggedIn ? $userController->getUserRole($userController->getUserID()) : null;
$postId = isset($_GET['id']) ? $_GET['id'] : null; // Pobierz ID posta z URL
$postController = new PostController();
$post = $postController->getPostByID($postId); // Pobierz post na podstawie ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'] ?? null;

    if (empty($title) || empty($content)) {
        header("Location: editPost.php?id=$postId&error=empty fields");
        exit();
    }

    if (empty($image)) {
        $image = $post['image'];
    } else {
        $target = '../storage/' . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $image = $target;
    }

    $postController->editPost($postId, $title, $content, $image);

    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szlakiem Przygód</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
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
            <div class="d-flex justify-content-start align-items-center logo">
                <a href="index.php">
                    <img src="./img/logo.png" alt="Logo" class="img-fluid" style="height: 120px; width: auto">
                </a>
                <h1 class="text-center flex-grow-1 mb-0 logo">Szlakiem Przygód</h1>
            </div>
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
    <hr class="my-4">
    <h2 class="text-center mt-5">Edytuj Post</h2>
    <main class="py-4">
        <form action="editPost.php?id=<?php echo $post['id']?>" method="post" enctype="multipart/form-data">
            <label for="title">Tytuł:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $post['title']; ?>" class="form-control"><br>
            <label for="content">Zawartość:</label><br>
            <textarea id="content" name="content" class="form-control"><?php echo $post['content']; ?></textarea><br>
            <label for="image">Obrazek (opcjonalnie):</label><br>
            <?php if ($post['image']): ?>
                <img src="<?= $post['image'] ?>" alt="Current Image" width="200" class="m-5"><br>
            <?php endif; ?>
            <input type="file" id="image" name="image" class="form-control"><br>
            <input type="submit" value="Zapisz zmiany" class="btn btn-primary">
        </form>
    </main>
</div>
<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>Indeks: s29420</p>
    </div>
</footer>
<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
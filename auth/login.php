<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Gold Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="login-page">
    <div class="card login-card">
        <div class="brand">
            <div class="logo">💰</div>
            <h2>Gold Shop</h2>
            <p class="sub">Sign in to your management console</p>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . e($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="login_process.php" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@goldshop.com" required autofocus>
            </div>
            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-warning w-100 py-2">Sign in</button>
        </form>
    </div>
</div>
</body>
</html>

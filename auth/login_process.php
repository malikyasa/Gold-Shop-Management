<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

require_post();
require_once __DIR__ . '/../config/database.php';

// Simple brute-force throttle: 5 failures -> 60s lockout
if (($_SESSION['fail_count'] ?? 0) >= 5 && time() - ($_SESSION['fail_time'] ?? 0) < 60) {
    $_SESSION['error'] = "Too many attempts. Please wait a minute and try again.";
    redirect('login.php');
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id']     = (int)$user['id'];
    $_SESSION['full_name']   = $user['full_name'];
    $_SESSION['role']        = $user['role'];
    $_SESSION['last_active'] = time();
    unset($_SESSION['fail_count'], $_SESSION['fail_time']);

    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $new = password_hash($password, PASSWORD_DEFAULT);
        $u = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $u->bind_param("si", $new, $user['id']);
        $u->execute();
    }
    redirect('../dashboard/index.php');
}

$_SESSION['fail_count'] = ($_SESSION['fail_count'] ?? 0) + 1;
$_SESSION['fail_time']  = time();
$_SESSION['error'] = "Invalid email or password";
redirect('login.php');

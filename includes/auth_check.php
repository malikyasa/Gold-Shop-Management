<?php
// Include at the top of every protected page. Starts a hardened session,
// loads the DB connection + helpers and redirects guests to the login page.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

require_once __DIR__ . '/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect(BASE_URL . '/auth/login.php');
}

// Auto logout after 30 minutes of inactivity
if (isset($_SESSION['last_active']) && time() - $_SESSION['last_active'] > 1800) {
    session_unset();
    session_destroy();
    session_start();
    redirect(BASE_URL . '/auth/login.php');
}
$_SESSION['last_active'] = time();

require_once __DIR__ . '/../config/database.php';

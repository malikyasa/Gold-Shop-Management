<?php
// Shared helpers: escaping, CSRF, flash messages, role checks.

// Auto-detect the URL path of this project (works for any folder name / nesting).
if (!defined('BASE_URL')) {
    $root = str_replace('\\', '/', (string)realpath(dirname(__DIR__)));
    $doc  = str_replace('\\', '/', (string)realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $base = '';
    if ($doc !== '' && stripos($root, $doc) === 0) {
        $base = substr($root, strlen($doc));
    }
    define('BASE_URL', rtrim($base, '/'));
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function money($value): string {
    return number_format((float)$value, 2);
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void {
    $sent = $_POST['csrf'] ?? '';
    $known = $_SESSION['csrf'] ?? '';
    if ($known === '' || !is_string($sent) || !hash_equals($known, $sent)) {
        http_response_code(419);
        exit('Invalid or expired form token. Go back, refresh the page and try again.');
    }
}

function require_post(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed.');
    }
    csrf_verify();
}

function flash(string $type, string $msg): void {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($f['type']) . '">' . e($f['msg']) . '</div>';
    }
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit();
}

function is_admin(): bool {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function require_admin(): void {
    if (!is_admin()) {
        http_response_code(403);
        exit('Access denied: administrators only.');
    }
}

function delete_button(string $action, int $id, string $confirm = 'Are you sure?'): string {
    return '<form method="POST" action="' . e($action) . '" class="d-inline" onsubmit="return confirm(\'' . e($confirm) . '\')">'
        . csrf_field()
        . '<input type="hidden" name="id" value="' . $id . '">'
        . '<button class="btn btn-danger btn-sm">Delete</button></form>';
}

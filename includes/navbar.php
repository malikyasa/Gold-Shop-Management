<nav class="navbar navbar-dark bg-dark text-white px-4">
    <span class="navbar-brand">Gold Shop Management System</span>
    <div>
        Welcome, <?= e($_SESSION['full_name'] ?? '') ?>
        <span class="badge bg-warning text-dark ms-1"><?= e($_SESSION['role'] ?? '') ?></span>
    </div>
</nav>

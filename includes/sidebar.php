<?php
$cur = $_SERVER['SCRIPT_NAME'] ?? '';
$nav = [
    ['/dashboard/',          '/dashboard/index.php',                     'fa-gauge',         'Dashboard'],
    ['/modules/inventory/',  '/modules/inventory/view_items.php',        'fa-gem',           'Inventory'],
    ['/modules/customers/',  '/modules/customers/view_customers.php',    'fa-users',         'Customers'],
    ['/modules/sales/',      '/modules/sales/sales_history.php',         'fa-cart-shopping', 'Sales'],
    ['/modules/purchases/',  '/modules/purchases/purchase_history.php',  'fa-truck',         'Purchases'],
];
?>
<div class="sidebar p-3">
    <h4 class="text-center mb-4">💰 Gold Shop</h4>
    <?php foreach ($nav as [$prefix, $href, $icon, $label]): ?>
        <a href="<?= BASE_URL . $href ?>" class="<?= strpos($cur, $prefix) !== false ? 'active' : '' ?>">
            <i class="fa <?= $icon ?>"></i> <?= $label ?>
        </a>
    <?php endforeach; ?>
    <a href="<?= BASE_URL ?>/auth/logout.php" class="logout"><i class="fa fa-right-from-bracket"></i> Logout</a>
</div>

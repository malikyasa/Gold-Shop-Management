<?php
require_once __DIR__ . '/../includes/auth_check.php';
$page_title = 'Dashboard - Gold Shop';

$one = fn($sql) => $conn->query($sql)->fetch_row()[0] ?? 0;
$customers = $one("SELECT COUNT(*) FROM customers");
$sales     = $one("SELECT COUNT(*) FROM sales");
$items     = $one("SELECT COUNT(*) FROM gold_items");
$purchases = $one("SELECT COUNT(*) FROM purchases");
$revenue   = $one("SELECT COALESCE(SUM(total_amount),0) FROM sales");
$spent     = $one("SELECT COALESCE(SUM(total_amount),0) FROM purchases");
$low       = $conn->query("SELECT item_name, stock_quantity FROM gold_items WHERE stock_quantity <= 5 ORDER BY stock_quantity ASC LIMIT 10");
$recent    = $conn->query("SELECT s.id, c.customer_name, s.total_amount, s.sale_date FROM sales s JOIN customers c ON c.id=s.customer_id ORDER BY s.id DESC LIMIT 5");

include __DIR__ . '/../includes/layout_top.php';
?>

<div class="main">
    <h2 class="mb-4">Dashboard</h2>
    <?php show_flash(); ?>

    <div class="row g-4">
        <div class="col-md-3"><div class="card card-box p-3"><h4>Customers</h4><h2><?= (int)$customers ?></h2></div></div>
        <div class="col-md-3"><div class="card card-box s-sales p-3"><h4>Sales</h4><h2><?= (int)$sales ?></h2></div></div>
        <div class="col-md-3"><div class="card card-box s-items p-3"><h4>Gold Items</h4><h2><?= (int)$items ?></h2></div></div>
        <div class="col-md-3"><div class="card card-box s-purch p-3"><h4>Purchases</h4><h2><?= (int)$purchases ?></h2></div></div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-6"><div class="card shadow p-3"><h5>Total Sales Revenue</h5><h3 class="text-success mb-0">PKR <?= money($revenue) ?></h3></div></div>
        <div class="col-md-6"><div class="card shadow p-3"><h5>Total Purchase Spending</h5><h3 class="text-danger mb-0">PKR <?= money($spent) ?></h3></div></div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5>Low Stock (5 or fewer)</h5>
                <table class="table table-sm mb-0">
                    <?php if ($low->num_rows === 0): ?><tr><td class="text-muted">All items well stocked.</td></tr><?php endif; ?>
                    <?php while ($r = $low->fetch_assoc()): ?>
                        <tr><td><?= e($r['item_name']) ?></td><td class="text-end"><span class="badge bg-danger"><?= (int)$r['stock_quantity'] ?></span></td></tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5>Recent Sales</h5>
                <table class="table table-sm mb-0">
                    <?php if ($recent->num_rows === 0): ?><tr><td class="text-muted">No sales yet.</td></tr><?php endif; ?>
                    <?php while ($r = $recent->fetch_assoc()): ?>
                        <tr><td><a href="<?= BASE_URL ?>/modules/sales/invoice.php?id=<?= (int)$r['id'] ?>">#<?= (int)$r['id'] ?></a> <?= e($r['customer_name']) ?></td><td class="text-end">PKR <?= money($r['total_amount']) ?></td></tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

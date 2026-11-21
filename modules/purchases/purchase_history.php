<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'Purchases - Gold Shop';
$rows = $conn->query("SELECT p.*, i.item_name FROM purchases p JOIN gold_items i ON i.id = p.item_id ORDER BY p.id DESC")->fetch_all(MYSQLI_ASSOC);
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
    <div class="page-heading mb-4">
        <div>
            <h3>Purchase History</h3>
            <p class="text-muted">Review recent purchase records and supplier activity.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="add_purchase.php" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Purchase</a>
            <a href="<?= BASE_URL ?>/modules/sales/sales_history.php" class="btn btn-outline-primary"><i class="fa fa-shopping-cart me-2"></i> Sales</a>
        </div>
    </div>
    <?php show_flash(); ?>
    <div class="card shadow-sm p-4">
        <?php if ($rows) : ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>ID</th><th>Item</th><th>Supplier</th><th>Quantity</th><th>Unit Price</th><th>Total</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($rows as $p) : ?>
                        <tr>
                            <td><?= (int)$p['id'] ?></td>
                            <td><?= e($p['item_name']) ?></td>
                            <td><?= e($p['supplier'] ?: '-') ?></td>
                            <td><?= (int)$p['quantity'] ?></td>
                            <td><?= money($p['unit_price']) ?></td>
                            <td><?= money($p['total_amount']) ?></td>
                            <td><?= e($p['purchase_date']) ?></td>
                            <td><?php if (is_admin()) echo delete_button('delete_purchase.php', (int)$p['id'], 'Delete this purchase? Stock will be reduced.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="empty-state">
                <i class="fa fa-box-open fa-3x"></i>
                <h4>No purchase records found</h4>
                <p>There are no purchases recorded yet.</p>
                <a href="add_purchase.php" class="btn btn-outline-primary">Create a Purchase</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

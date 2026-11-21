<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'Sales - Gold Shop';
$sales = $conn->query("
    SELECT sales.*, customers.customer_name
    FROM sales JOIN customers ON sales.customer_id = customers.id
    ORDER BY sales.id DESC");
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Sales History</h3>
    <a href="add_sale.php" class="btn btn-success">+ New Sale</a>
</div>
<?php show_flash(); ?>
<div class="card shadow p-3">
<div class="table-responsive">
<table class="table table-bordered table-hover text-center">
<thead class="table-dark"><tr><th>ID</th><th>Customer</th><th>Total (PKR)</th><th>Date</th><th>Actions</th></tr></thead>
<tbody>
<?php while($s = $sales->fetch_assoc()) { ?>
<tr>
    <td><?= (int)$s['id'] ?></td>
    <td><?= e($s['customer_name']) ?></td>
    <td><?= money($s['total_amount']) ?></td>
    <td><?= e($s['sale_date']) ?></td>
    <td>
        <a href="invoice.php?id=<?= (int)$s['id'] ?>" class="btn btn-primary btn-sm">Invoice</a>
        <?php if (is_admin()) echo delete_button('delete_sale.php', (int)$s['id'], 'Delete this sale? Stock will be restored.'); ?>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

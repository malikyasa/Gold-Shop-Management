<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("
    SELECT s.id, s.total_amount, s.sale_date, c.customer_name, c.phone,
           i.item_name, i.purity, i.weight, si.price, si.quantity, si.subtotal
    FROM sales s
    JOIN customers c ON c.id = s.customer_id
    JOIN sale_items si ON si.sale_id = s.id
    JOIN gold_items i ON i.id = si.item_id
    WHERE s.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$inv = $stmt->get_result()->fetch_assoc();
if (!$inv) { flash('danger', 'Invoice not found.'); redirect('sales_history.php'); }
$page_title = 'Invoice #' . $id;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>@media print { .no-print { display: none !important; } }</style>
</head>
<body>
<div class="invoice-box card shadow p-4 mt-4">
<h2 class="text-center">💰 Gold Shop Invoice</h2>
<p class="text-center text-muted mb-0">Invoice #<?= (int)$inv['id'] ?> &middot; <?= e($inv['sale_date']) ?></p>
<hr>
<p><b>Customer:</b> <?= e($inv['customer_name']) ?></p>
<p><b>Phone:</b> <?= e($inv['phone']) ?></p>
<hr>
<table class="table table-bordered text-center">
<tr><th>Item</th><th>Purity</th><th>Weight</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
<tr>
    <td><?= e($inv['item_name']) ?></td>
    <td><?= e($inv['purity']) ?></td>
    <td><?= e($inv['weight']) ?> g</td>
    <td><?= money($inv['price']) ?></td>
    <td><?= (int)$inv['quantity'] ?></td>
    <td><?= money($inv['subtotal']) ?></td>
</tr>
</table>
<h4 class="text-end">Grand Total: <?= money($inv['total_amount']) ?> PKR</h4>
<div class="text-center mt-3 no-print">
    <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
    <a href="add_sale.php" class="btn btn-success">New Sale</a>
    <a href="sales_history.php" class="btn btn-secondary">Sales History</a>
</div>
</div>
</body>
</html>

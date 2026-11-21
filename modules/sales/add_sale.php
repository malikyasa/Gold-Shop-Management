<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'New Sale - Gold Shop';
$customers = $conn->query("SELECT id, customer_name FROM customers ORDER BY customer_name");
$items = $conn->query("SELECT id, item_name, stock_quantity, sale_price FROM gold_items WHERE stock_quantity > 0 ORDER BY item_name");
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
<h3>Create New Sale</h3>
<?php show_flash(); ?>
<div class="card p-4 shadow">
<form method="POST" action="process_sale.php">
<?= csrf_field() ?>
<div class="mb-3">
<label>Select Customer</label>
<select name="customer_id" class="form-control" required>
    <option value="">Select Customer</option>
    <?php while($c = $customers->fetch_assoc()) { ?>
        <option value="<?= (int)$c['id'] ?>"><?= e($c['customer_name']) ?></option>
    <?php } ?>
</select>
</div>
<div class="mb-3">
<label>Select Item</label>
<select name="item_id" class="form-control" required>
    <option value="">Select Item</option>
    <?php while($i = $items->fetch_assoc()) { ?>
        <option value="<?= (int)$i['id'] ?>"><?= e($i['item_name']) ?> (Stock: <?= (int)$i['stock_quantity'] ?>, PKR <?= money($i['sale_price']) ?>)</option>
    <?php } ?>
</select>
</div>
<div class="mb-3">
<label>Quantity</label>
<input type="number" name="quantity" min="1" value="1" class="form-control" required>
</div>
<button class="btn btn-success">Generate Invoice</button>
</form>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

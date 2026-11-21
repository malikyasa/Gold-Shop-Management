<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'Add Purchase - Gold Shop';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $item_id  = (int)($_POST['item_id'] ?? 0);
    $supplier = trim($_POST['supplier'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $unit     = $_POST['unit_price'] ?? '';
    if ($item_id < 1 || $quantity < 1 || !is_numeric($unit) || $unit < 0) {
        flash('danger', 'Choose an item and enter a valid quantity and unit price.');
    } else {
        $total = $unit * $quantity;
        $uid = (int)$_SESSION['user_id'];
        $conn->begin_transaction();
        try {
            $p = $conn->prepare("INSERT INTO purchases(item_id, supplier, quantity, unit_price, total_amount, purchase_date, created_by) VALUES (?,?,?,?,?,NOW(),?)");
            $p->bind_param("isiddi", $item_id, $supplier, $quantity, $unit, $total, $uid);
            $p->execute();
            $u = $conn->prepare("UPDATE gold_items SET stock_quantity = stock_quantity + ?, purchase_price = ? WHERE id=?");
            $u->bind_param("idi", $quantity, $unit, $item_id);
            $u->execute();
            $conn->commit();
            flash('success', 'Purchase recorded and stock updated.');
            redirect('purchase_history.php');
        } catch (Throwable $ex) {
            $conn->rollback();
            error_log($ex->getMessage());
            flash('danger', 'Could not save the purchase.');
        }
    }
}
$items = $conn->query("SELECT id, item_name, stock_quantity FROM gold_items ORDER BY item_name");
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
<h3>Add Purchase</h3>
<?php show_flash(); ?>
<div class="card p-4 shadow">
<form method="POST">
<?= csrf_field() ?>
<div class="mb-3"><label>Item</label>
<select name="item_id" class="form-control" required>
    <option value="">Select Item</option>
    <?php while($i = $items->fetch_assoc()) { ?>
        <option value="<?= (int)$i['id'] ?>"><?= e($i['item_name']) ?> (Stock: <?= (int)$i['stock_quantity'] ?>)</option>
    <?php } ?>
</select></div>
<div class="mb-3"><label>Supplier</label><input type="text" name="supplier" class="form-control" maxlength="150"></div>
<div class="mb-3"><label>Quantity</label><input type="number" name="quantity" min="1" value="1" class="form-control" required></div>
<div class="mb-3"><label>Unit Price (PKR)</label><input type="number" step="0.01" min="0" name="unit_price" class="form-control" required></div>
<button class="btn btn-success">Save Purchase</button>
<a href="purchase_history.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

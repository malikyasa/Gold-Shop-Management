<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/_save.php';
$page_title = 'Add Item - Gold Shop';
$data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    [$data, $err] = read_item_post();
    if ($err) {
        flash('danger', $err);
    } else {
        $stmt = $conn->prepare("INSERT INTO gold_items (item_name, category, purity, weight, purchase_price, sale_price, stock_quantity) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssdddi", $data['item_name'], $data['category'], $data['purity'], $data['weight'], $data['purchase_price'], $data['sale_price'], $data['stock_quantity']);
        $stmt->execute();
        flash('success', 'Item added.');
        redirect('view_items.php');
    }
}
$submit_label = 'Save Item';
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
    <h2>Add Gold Item</h2>
    <?php show_flash(); ?>
    <div class="card shadow p-4 mt-3"><?php include __DIR__ . '/_form.php'; ?></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

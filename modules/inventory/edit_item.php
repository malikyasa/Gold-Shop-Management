<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/_save.php';
$page_title = 'Edit Item - Gold Shop';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM gold_items WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) { flash('danger', 'Item not found.'); redirect('view_items.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    [$posted, $err] = read_item_post();
    $posted['id'] = $id;
    $data = $posted;
    if ($err) {
        flash('danger', $err);
    } else {
        $u = $conn->prepare("UPDATE gold_items SET item_name=?, category=?, purity=?, weight=?, purchase_price=?, sale_price=?, stock_quantity=? WHERE id=?");
        $u->bind_param("sssdddii", $data['item_name'], $data['category'], $data['purity'], $data['weight'], $data['purchase_price'], $data['sale_price'], $data['stock_quantity'], $id);
        $u->execute();
        flash('success', 'Item updated.');
        redirect('view_items.php');
    }
}
$submit_label = 'Update Item';
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
    <h2>Edit Gold Item</h2>
    <?php show_flash(); ?>
    <div class="card shadow p-4 mt-3"><?php include __DIR__ . '/_form.php'; ?></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'Edit Customer - Gold Shop';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) { flash('danger', 'Customer not found.'); redirect('view_customers.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    if ($name === '') {
        flash('danger', 'Customer name is required.');
    } else {
        $u = $conn->prepare("UPDATE customers SET customer_name=?, phone=?, email=?, address=? WHERE id=?");
        $u->bind_param("ssssi", $name, $phone, $email, $address, $id);
        $u->execute();
        flash('success', 'Customer updated.');
        redirect('view_customers.php');
    }
}
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
<h3>Edit Customer</h3>
<?php show_flash(); ?>
<div class="card p-4 shadow">
<form method="POST">
<?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $id ?>">
<input type="text" name="customer_name" value="<?= e($data['customer_name']) ?>" class="form-control mb-2" required>
<input type="text" name="phone" value="<?= e($data['phone']) ?>" class="form-control mb-2">
<input type="email" name="email" value="<?= e($data['email']) ?>" class="form-control mb-2">
<textarea name="address" class="form-control mb-3"><?= e($data['address']) ?></textarea>
<button class="btn btn-primary">Update</button>
<a href="view_customers.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

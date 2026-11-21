<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'Add Customer - Gold Shop';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    if ($name === '') {
        flash('danger', 'Customer name is required.');
    } else {
        $stmt = $conn->prepare("INSERT INTO customers(customer_name, phone, email, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $address);
        $stmt->execute();
        flash('success', 'Customer added.');
        redirect('view_customers.php');
    }
}
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
<h3>Add Customer</h3>
<?php show_flash(); ?>
<div class="card p-4 shadow">
<form method="POST">
<?= csrf_field() ?>
<div class="mb-2"><label>Name</label><input type="text" name="customer_name" class="form-control" required maxlength="120"></div>
<div class="mb-2"><label>Phone</label><input type="text" name="phone" class="form-control" maxlength="30"></div>
<div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" maxlength="150"></div>
<div class="mb-3"><label>Address</label><textarea name="address" class="form-control"></textarea></div>
<button class="btn btn-success">Save Customer</button>
<a href="view_customers.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

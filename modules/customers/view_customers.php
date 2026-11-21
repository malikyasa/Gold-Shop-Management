<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'Customers - Gold Shop';
$search = trim($_GET['q'] ?? '');
$like = "%$search%";
$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_name LIKE ? OR phone LIKE ? OR email LIKE ? ORDER BY id DESC");
$stmt->bind_param("sss", $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Customer Management</h3>
    <a href="add_customer.php" class="btn btn-success">+ Add Customer</a>
</div>
<?php show_flash(); ?>
<form class="mb-3" method="GET"><input type="search" name="q" value="<?= e($search) ?>" class="form-control" placeholder="Search name, phone or email..."></form>
<div class="card shadow p-3">
<div class="table-responsive">
<table class="table table-bordered table-hover text-center">
<thead class="table-dark"><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= (int)$row['id'] ?></td>
    <td><?= e($row['customer_name']) ?></td>
    <td><?= e($row['phone']) ?></td>
    <td><?= e($row['email']) ?></td>
    <td><?= e($row['address']) ?></td>
    <td>
        <a href="edit_customer.php?id=<?= (int)$row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
        <?php if (is_admin()) echo delete_button('delete_customer.php', (int)$row['id'], 'Delete this customer?'); ?>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

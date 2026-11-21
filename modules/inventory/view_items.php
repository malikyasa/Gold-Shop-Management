<?php
require_once __DIR__ . '/../../includes/auth_check.php';
$page_title = 'Inventory - Gold Shop';
$search = trim($_GET['q'] ?? '');
$like = "%$search%";
$stmt = $conn->prepare("SELECT * FROM gold_items WHERE item_name LIKE ? OR category LIKE ? OR purity LIKE ? ORDER BY id DESC");
$stmt->bind_param("sss", $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();
include __DIR__ . '/../../includes/layout_top.php';
?>
<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gold Inventory</h2>
        <a href="add_item.php" class="btn btn-success">+ Add New Item</a>
    </div>
    <?php show_flash(); ?>
    <form class="mb-3" method="GET"><input type="search" name="q" value="<?= e($search) ?>" class="form-control" placeholder="Search item, category or purity..."></form>
    <div class="card shadow p-3">
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr><th>ID</th><th>Item Name</th><th>Category</th><th>Purity</th><th>Weight</th><th>Stock</th><th>Purchase Price</th><th>Sale Price</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= (int)$row['id'] ?></td>
                    <td><?= e($row['item_name']) ?></td>
                    <td><?= e($row['category']) ?></td>
                    <td><?= e($row['purity']) ?></td>
                    <td><?= e($row['weight']) ?> g</td>
                    <td>
                        <?= (int)$row['stock_quantity'] ?>
                        <?php if($row['stock_quantity'] > 5) { ?>
                            <span class="badge bg-success">In Stock</span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Low Stock</span>
                        <?php } ?>
                    </td>
                    <td><?= money($row['purchase_price']) ?></td>
                    <td><?= money($row['sale_price']) ?></td>
                    <td>
                        <a href="edit_item.php?id=<?= (int)$row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <?php if (is_admin()) echo delete_button('delete_item.php', (int)$row['id'], 'Delete this item?'); ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

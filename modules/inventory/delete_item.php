<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_post();
require_admin();
$id = (int)($_POST['id'] ?? 0);
try {
    $stmt = $conn->prepare("DELETE FROM gold_items WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    flash('success', 'Item deleted.');
} catch (mysqli_sql_exception $e) {
    flash('danger', 'Cannot delete an item that has sales or purchase records.');
}
redirect('view_items.php');

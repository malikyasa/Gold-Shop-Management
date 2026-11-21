<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_post();
require_admin();
$id = (int)($_POST['id'] ?? 0);
try {
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    flash('success', 'Customer deleted.');
} catch (mysqli_sql_exception $e) {
    flash('danger', 'Cannot delete a customer who has sales records.');
}
redirect('view_customers.php');

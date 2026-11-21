<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_post();
require_admin();
$id = (int)($_POST['id'] ?? 0);

$conn->begin_transaction();
try {
    // Put the sold quantity back into stock, then remove the sale
    $r = $conn->prepare("UPDATE gold_items gi JOIN sale_items si ON si.item_id = gi.id
                         SET gi.stock_quantity = gi.stock_quantity + si.quantity WHERE si.sale_id = ?");
    $r->bind_param("i", $id);
    $r->execute();
    $d = $conn->prepare("DELETE FROM sales WHERE id=?");   // sale_items cascade
    $d->bind_param("i", $id);
    $d->execute();
    $conn->commit();
    flash('success', 'Sale deleted and stock restored.');
} catch (Throwable $ex) {
    $conn->rollback();
    error_log($ex->getMessage());
    flash('danger', 'Could not delete the sale.');
}
redirect('sales_history.php');

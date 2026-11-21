<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_post();
require_admin();
$id = (int)($_POST['id'] ?? 0);

$conn->begin_transaction();
try {
    $g = $conn->prepare("SELECT item_id, quantity FROM purchases WHERE id=? FOR UPDATE");
    $g->bind_param("i", $id);
    $g->execute();
    $p = $g->get_result()->fetch_assoc();
    if ($p) {
        $chk = $conn->prepare("SELECT stock_quantity FROM gold_items WHERE id=? FOR UPDATE");
        $chk->bind_param("i", $p['item_id']);
        $chk->execute();
        $stock = (int)$chk->get_result()->fetch_row()[0];
        if ($stock < $p['quantity']) throw new RuntimeException('Cannot delete: that stock has already been sold.');
        $u = $conn->prepare("UPDATE gold_items SET stock_quantity = stock_quantity - ? WHERE id=?");
        $u->bind_param("ii", $p['quantity'], $p['item_id']);
        $u->execute();
        $d = $conn->prepare("DELETE FROM purchases WHERE id=?");
        $d->bind_param("i", $id);
        $d->execute();
    }
    $conn->commit();
    flash('success', 'Purchase deleted and stock adjusted.');
} catch (RuntimeException $ex) {
    $conn->rollback();
    flash('danger', $ex->getMessage());
} catch (Throwable $ex) {
    $conn->rollback();
    error_log($ex->getMessage());
    flash('danger', 'Could not delete the purchase.');
}
redirect('purchase_history.php');

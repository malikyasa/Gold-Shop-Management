<?php
require_once __DIR__ . '/../../includes/auth_check.php';
require_post();

$customer_id = (int)($_POST['customer_id'] ?? 0);
$item_id     = (int)($_POST['item_id'] ?? 0);
$quantity    = (int)($_POST['quantity'] ?? 0);
$user_id     = (int)$_SESSION['user_id'];

if ($customer_id < 1 || $item_id < 1 || $quantity < 1) {
    flash('danger', 'Please choose a customer, an item and a valid quantity.');
    redirect('add_sale.php');
}

$conn->begin_transaction();
try {
    // Lock the item row so concurrent sales cannot oversell stock
    $s = $conn->prepare("SELECT sale_price, stock_quantity FROM gold_items WHERE id=? FOR UPDATE");
    $s->bind_param("i", $item_id);
    $s->execute();
    $item = $s->get_result()->fetch_assoc();
    if (!$item) throw new RuntimeException('Item not found.');
    if ($item['stock_quantity'] < $quantity) throw new RuntimeException('Not enough stock. Available: ' . $item['stock_quantity']);

    $c = $conn->prepare("SELECT id FROM customers WHERE id=?");
    $c->bind_param("i", $customer_id);
    $c->execute();
    if (!$c->get_result()->fetch_assoc()) throw new RuntimeException('Customer not found.');

    $price = $item['sale_price'];
    $total = $price * $quantity;

    $i = $conn->prepare("INSERT INTO sales(customer_id, total_amount, sale_date, created_by) VALUES (?,?,NOW(),?)");
    $i->bind_param("idi", $customer_id, $total, $user_id);
    $i->execute();
    $sale_id = $conn->insert_id;

    $si = $conn->prepare("INSERT INTO sale_items(sale_id, item_id, quantity, price, subtotal) VALUES (?,?,?,?,?)");
    $si->bind_param("iiidd", $sale_id, $item_id, $quantity, $price, $total);
    $si->execute();

    $u = $conn->prepare("UPDATE gold_items SET stock_quantity = stock_quantity - ? WHERE id=?");
    $u->bind_param("ii", $quantity, $item_id);
    $u->execute();

    $conn->commit();
    redirect('invoice.php?id=' . $sale_id);
} catch (RuntimeException $ex) {
    $conn->rollback();
    flash('danger', $ex->getMessage());
    redirect('add_sale.php');
} catch (Throwable $ex) {
    $conn->rollback();
    error_log($ex->getMessage());
    flash('danger', 'Could not save the sale. Please try again.');
    redirect('add_sale.php');
}

<?php
// Validates posted item fields. Returns [values, error|null].
function read_item_post(): array {
    $v = [
        'item_name' => trim($_POST['item_name'] ?? ''),
        'category'  => trim($_POST['category'] ?? ''),
        'purity'    => trim($_POST['purity'] ?? ''),
        'weight'    => $_POST['weight'] ?? '',
        'purchase_price' => $_POST['purchase_price'] ?? '',
        'sale_price'     => $_POST['sale_price'] ?? '',
        'stock_quantity' => $_POST['stock_quantity'] ?? '',
    ];
    $err = null;
    if ($v['item_name'] === '') $err = 'Item name is required.';
    foreach (['weight','purchase_price','sale_price'] as $f) {
        if (!is_numeric($v[$f]) || $v[$f] < 0) $err = $err ?? "Invalid value for $f.";
    }
    if (filter_var($v['stock_quantity'], FILTER_VALIDATE_INT) === false || $v['stock_quantity'] < 0) $err = $err ?? 'Invalid stock quantity.';
    return [$v, $err];
}

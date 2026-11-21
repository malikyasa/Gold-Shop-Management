<?php
// Shared item form. Expects $data (array) and $submit_label.
?>
<form method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)($data['id'] ?? 0) ?>">
    <div class="row">
        <div class="col-md-6 mb-3"><label>Item Name</label><input type="text" name="item_name" value="<?= e($data['item_name'] ?? '') ?>" class="form-control" required maxlength="150"></div>
        <div class="col-md-6 mb-3"><label>Category</label><input type="text" name="category" value="<?= e($data['category'] ?? '') ?>" class="form-control" maxlength="80"></div>
        <div class="col-md-6 mb-3"><label>Purity</label><input type="text" name="purity" value="<?= e($data['purity'] ?? '') ?>" class="form-control" placeholder="22K / 24K" maxlength="20"></div>
        <div class="col-md-6 mb-3"><label>Weight (grams)</label><input type="number" step="0.01" min="0" name="weight" value="<?= e($data['weight'] ?? '') ?>" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label>Purchase Price</label><input type="number" step="0.01" min="0" name="purchase_price" value="<?= e($data['purchase_price'] ?? '') ?>" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label>Sale Price</label><input type="number" step="0.01" min="0" name="sale_price" value="<?= e($data['sale_price'] ?? '') ?>" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label>Stock Quantity</label><input type="number" min="0" name="stock_quantity" value="<?= e($data['stock_quantity'] ?? '') ?>" class="form-control" required></div>
    </div>
    <button type="submit" class="btn btn-success"><?= e($submit_label) ?></button>
    <a href="view_items.php" class="btn btn-secondary">Cancel</a>
</form>

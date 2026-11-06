<?php
/**
 * views/add_product.php - Add a new product
 *
 * Requires: $products (existing products, for Product Name suggestions)
 */
$existingProducts = [];
foreach ($products ?? [] as $product) {
    $existingProducts[] = [
        'name' => (string)($product['name'] ?? ''),
        'code' => (string)($product['product_id'] ?? ''),
        'category' => (string)($product['category'] ?? ''),
        'price' => (float)($product['price'] ?? 0),
    ];
}
?>
<div class="form-card add-product-card">
    <h2>New Product</h2>

    <form method="POST" action="dashboard.php?view=add">
        <input type="hidden" name="action" value="add">

        <div class="form-grid">
            <div class="form-group product-name-group">
                <label>Product Name <span class="req">*</span></label>
                <div class="pid-wrap">
                    <input type="text" name="name" id="f_name" autocomplete="off" required
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    <div class="pid-list" id="productNameList"></div>
                </div>
            </div>

            <div class="form-group product-id-group">
                <label>Product-ID <span class="req">*</span></label>
                <input type="text" name="product_id" id="f_product_id"
                       value="<?php echo htmlspecialchars($_POST['product_id'] ?? ''); ?>">
            </div>

            <div class="form-group product-category-group">
                <label>Category</label>
                <input type="text" name="category" id="f_category"
                       value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
            </div>

            <div class="form-group product-price-group">
                <label>Price (Rs.) <span class="req">*</span></label>
                <input type="number" name="price" id="f_price" step="0.01" min="0" required
                       value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
            </div>

            <div class="form-group product-quantity-group">
                <label>Initial Quantity <span class="req">*</span></label>
                <input type="number" name="quantity" min="0" step="1" required
                       value="<?php echo htmlspecialchars($_POST['quantity'] ?? ''); ?>">
            </div>

            <div class="form-group full product-description-group">
                <label>Description</label>
                <textarea name="description" rows="2"
                          placeholder="Optional notes about this product">
<?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <a href="dashboard.php?view=list" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </div>
    </form>
</div>

<script type="application/json" id="existing-products-data"><?php
    echo json_encode($existingProducts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?></script>

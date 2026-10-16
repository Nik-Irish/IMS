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

<script>
const existingProducts = <?php
    echo json_encode($existingProducts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>;

const productNameInput = document.getElementById('f_name');
const productNameList = document.getElementById('productNameList');
const productIdInput = document.getElementById('f_product_id');
const categoryInput = document.getElementById('f_category');
const priceInput = document.getElementById('f_price');
let autoFilledProduct = null;

function findProductByName(name) {
    const query = name.trim().toLowerCase();
    return existingProducts.find(product => product.name.toLowerCase() === query) || null;
}

function closeProductNameList() {
    productNameList.style.display = 'none';
}

function fillFromProduct(product) {
    productNameInput.value = product.name;
    productIdInput.value = product.code;
    categoryInput.value = product.category;
    priceInput.value = Number(product.price).toFixed(2);
    autoFilledProduct = {
        code: productIdInput.value,
        category: categoryInput.value,
        price: priceInput.value,
    };
}

function clearAutoFilledFields() {
    if (!autoFilledProduct) {
        return;
    }
    if (productIdInput.value === autoFilledProduct.code) productIdInput.value = '';
    if (categoryInput.value === autoFilledProduct.category) categoryInput.value = '';
    if (priceInput.value === autoFilledProduct.price) priceInput.value = '';
    autoFilledProduct = null;
}

function renderProductNameList() {
    const query = productNameInput.value.trim().toLowerCase();
    const matches = existingProducts.filter(product =>
        !query || product.name.toLowerCase().includes(query));

    productNameList.innerHTML = '';
    if (!matches.length) {
        closeProductNameList();
        return;
    }

    matches.forEach(product => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'pid-item';

        const name = document.createElement('span');
        name.className = 'pid-code';
        name.textContent = product.name;
        button.appendChild(name);

        const details = document.createElement('span');
        details.className = 'pid-meta';
        details.textContent = product.code + ' · ' + (product.category || 'General')
            + ' · Rs. ' + Number(product.price).toFixed(2);
        button.appendChild(details);

        button.addEventListener('mousedown', event => {
            event.preventDefault();
            fillFromProduct(product);
            closeProductNameList();
        });
        productNameList.appendChild(button);
    });
    productNameList.style.display = 'block';
}

productNameInput.addEventListener('focus', renderProductNameList);
productNameInput.addEventListener('input', () => {
    renderProductNameList();
    const product = findProductByName(productNameInput.value);
    if (product) {
        fillFromProduct(product);
        closeProductNameList();
    } else {
        clearAutoFilledFields();
    }
});

document.addEventListener('click', event => {
    if (!event.target.closest('.pid-wrap')) closeProductNameList();
});
</script>

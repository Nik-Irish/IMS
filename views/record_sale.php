<?php
/**
 * views/record_sale.php - Record a new sale
 *
 * Requires: $products (array)
 */
$dashboardScript = $dashboardScript ?? 'dashboard.php';
$existingCustomers = [];
foreach ($customers ?? [] as $customer) {
    $existingCustomers[] = [
        'name' => (string)($customer['customer_name'] ?? ''),
        'phone' => (string)($customer['customer_phone'] ?? ''),
    ];
}
?>
<div class="form-card sale-entry-card">
    <div class="sale-card-heading">
        <h2>Sale details</h2>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p>You need a product before you can record a sale.</p>
            <a href="<?php echo htmlspecialchars($dashboardScript); ?>?view=add" class="btn btn-primary">Add Your First Product</a>
        </div>
    <?php else: ?>
        <form method="POST" action="<?php echo htmlspecialchars($dashboardScript); ?>?view=sale_add">
            <input type="hidden" name="action" value="sale">

            <div class="form-grid">
                <div class="form-group">
                    <label>Customer Name <span class="req">*</span></label>
                    <input type="text" id="customer_name" name="customer_name"
                           value="<?php echo htmlspecialchars($_POST['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <div class="customer-select-wrap">
                        <input type="text" id="customer_phone" name="customer_phone" class="customer-select-input" autocomplete="off"
                           value="<?php echo htmlspecialchars($_POST['customer_phone'] ?? ''); ?>">
                        <div class="pid-list" id="customerList"></div>
                    </div>
                </div>      

                <div class="form-group full">
                    <label>Product <span class="req">*</span></label>
                    <select id="product_id" name="product_id" required>
                        <option value="">Select a product</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?php echo (int)$p['id']; ?>"
                                    data-price="<?php echo (float)$p['price']; ?>"
                                    data-stock="<?php echo (int)$p['quantity']; ?>">
                                <?php echo htmlspecialchars(
                                    $p['name']
                                    . ' (' . $p['product_id'] . ')'
                                    . ' · Stock: ' . $p['quantity']
                                ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Quantity <span class="req">*</span></label>
                    <input type="number" id="quantity" name="quantity"
                           min="1" step="1" required
                           value="<?php echo htmlspecialchars($_POST['quantity'] ?? '1'); ?>">
                </div>

                <div class="form-group">
                    <label>Unit Price (Rs.)</label>
                    <input type="number" id="unit_price" name="unit_price"
                           step="0.01" min="0" required readonly
                           value="<?php echo htmlspecialchars($_POST['unit_price'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Sale Date</label>
                    <input type="date" name="sale_date"
                           value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Note</label>
                    <input type="text" name="note"
                           value="<?php echo htmlspecialchars($_POST['note'] ?? ''); ?>" placeholder="Optional">
                </div>
            </div>

            <!-- Live VAT preview -->
            <div id="tax-preview"
                 class="sale-tax-preview"
                 style="display:none;">
                <span>Subtotal: <strong id="prev-sub">Rs.0.00</strong></span>
                &nbsp;|&nbsp;
                <span>VAT 13%: <strong id="prev-tax">Rs.0.00</strong></span>
                &nbsp;|&nbsp;
                <span>Total: <strong id="prev-total">Rs.0.00</strong></span>
            </div>

            <div class="form-actions">
                <a href="<?php echo htmlspecialchars($dashboardScript); ?>?view=sales" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Record Sale</button>
            </div>
        </form>

        <script>
        const existingCustomers = <?php
            echo json_encode($existingCustomers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        ?>;

        (function () {
            var sel   = document.getElementById('product_id');
            var upEl  = document.getElementById('unit_price');
            var qEl   = document.getElementById('quantity');
            var prev  = document.getElementById('tax-preview');
            var customerName = document.getElementById('customer_name');
            var customerPhone = document.getElementById('customer_phone');
            var customerList = document.getElementById('customerList');
            var selectedCustomerPhone = null;

            function closeCustomerList() {
                customerList.style.display = 'none';
            }

            function renderCustomerList() {
                var query = customerPhone.value.trim().toLowerCase();
                var matches = existingCustomers.filter(function (customer) {
                    return customer.phone && (!query || customer.phone.toLowerCase().includes(query));
                });

                customerList.innerHTML = '';
                if (!matches.length) {
                    closeCustomerList();
                    return;
                }

                matches.forEach(function (customer) {
                    var button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'pid-item';

                    var phone = document.createElement('span');
                    phone.className = 'pid-code';
                    phone.textContent = customer.phone;
                    button.appendChild(phone);

                    var name = document.createElement('span');
                    name.className = 'pid-meta';
                    name.textContent = customer.name;
                    button.appendChild(name);

                    button.addEventListener('mousedown', function (event) {
                        event.preventDefault();
                        customerName.value = customer.name;
                        customerPhone.value = customer.phone;
                        selectedCustomerPhone = customer.phone;
                        closeCustomerList();
                    });
                    customerList.appendChild(button);
                });
                customerList.style.display = 'block';
            }

            customerPhone.addEventListener('focus', renderCustomerList);
            customerPhone.addEventListener('input', function () {
                if (selectedCustomerPhone !== null && customerPhone.value !== selectedCustomerPhone) {
                    customerName.value = '';
                    selectedCustomerPhone = null;
                }
                renderCustomerList();
            });
            document.addEventListener('click', function (event) {
                if (!event.target.closest('.customer-select-wrap')) {
                    closeCustomerList();
                }
            });

            function fmt(n) {
                return 'Rs.' + parseFloat(n).toFixed(2);
            }

            function updatePreview() {
                var p = parseFloat(upEl.value) || 0;
                var q = parseInt(qEl.value)    || 0;
                if (p > 0 && q > 0) {
                    var sub = p * q;
                    var tax = sub * 0.13;
                    var tot = sub + tax;
                    document.getElementById('prev-sub').textContent   = fmt(sub);
                    document.getElementById('prev-tax').textContent   = fmt(tax);
                    document.getElementById('prev-total').textContent = fmt(tot);
                    prev.style.display = 'flex';
                } else {
                    prev.style.display = 'none';
                }
            }

            sel.addEventListener('change', function () {
                var o = this.options[this.selectedIndex];
                if (o.value) {
                    upEl.value = o.dataset.price || '';
                    qEl.max    = o.dataset.stock || '';
                }
                updatePreview();
            });

            upEl.addEventListener('input', updatePreview);
            qEl.addEventListener('input',  updatePreview);
        })();
        </script>
    <?php endif; ?>
</div>
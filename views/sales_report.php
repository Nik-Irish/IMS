<?php
/**
 * views/sales_report.php - Sales transaction list with filters
 *
 * Requires: $filteredSales, $products, $categories,
 *           $reportFrom, $reportTo, $reportProductId, $reportCategory
 */
$filteredSales = $filteredSales ?? [];
$products = $products ?? [];
$categories = $categories ?? [];
$reportFrom = $reportFrom ?? '';
$reportTo = $reportTo ?? '';
$reportProductId = $reportProductId ?? '';
$reportCategory = $reportCategory ?? '';
?>

<div class="sales-report-page">
<div class="toolbar sales-filter-toolbar">
    <form class="search-form sales-filter-form" method="GET" action="dashboard.php">
        <input type="hidden" name="view" value="sales">

        <div class="sales-filter-field">
            <label for="sales-filter-product">Product</label>
            <select id="sales-filter-product" name="product_id">
                <option value="">All products</option>
                <?php foreach ($products as $p): ?>
                    <option value="<?php echo htmlspecialchars($p['product_id']); ?>"
                        <?php echo (string)$reportProductId === (string)$p['product_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($p['name'] . ' (' . $p['product_id'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="sales-filter-field">
            <label for="sales-filter-category">Category</label>
            <select id="sales-filter-category" name="category">
                <option value="">All categories</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo htmlspecialchars($c); ?>"
                        <?php echo $reportCategory === $c ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="sales-filter-field">
            <label for="sales-filter-from">From</label>
            <input id="sales-filter-from" type="date" name="from"
                   value="<?php echo htmlspecialchars($reportFrom); ?>">
        </div>

        <div class="sales-filter-field">
            <label for="sales-filter-to">To</label>
            <input id="sales-filter-to" type="date" name="to"
                   value="<?php echo htmlspecialchars($reportTo); ?>">
        </div>

        <div class="sales-filter-actions">
            <button type="submit" class="btn btn-secondary">Apply filters</button>
            <?php if ($reportFrom !== '' || $reportTo !== '' || $reportProductId !== '' || $reportCategory !== ''): ?>
                <a href="dashboard.php?view=sales" class="btn btn-ghost">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="table-wrap sales-report-table">
    <div class="sales-report-heading">
        <h2>Sales transactions</h2>
    </div>
    <?php if (empty($filteredSales)): ?>
        <div class="empty-state">
            <p>No sales match these filters. Adjust or clear the filters to see more results.</p>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Bill #</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Customer</th>
                    <th>Recorded By</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($filteredSales as $s): ?>
                <tr>
                    <td><code><?php echo htmlspecialchars($s['bill_no']); ?></code></td>
                    <td>
                        <span><?php echo htmlspecialchars($s['product_name']); ?></span>
                        <?php if (!empty($s['product_id'])): ?>
                            <span class="sales-product-code">ID: <?php echo htmlspecialchars($s['product_id']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo (int)$s['quantity']; ?></td>
                    <td>Rs.<?php echo number_format((float)$s['unit_price'], 2); ?></td>
                    <td>Rs.<?php echo number_format((float)$s['total'], 2); ?></td>
                    <td><?php echo htmlspecialchars($s['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($s['staff_name'] ?? 'Admin'); ?></td>
                    <td><?php echo htmlspecialchars($s['sale_date']); ?></td>
                    <td>
                        <a href="dashboard.php?download=pdf&sale_id=<?php echo (int)$s['id']; ?>"
                           class="btn btn-sm btn-secondary">Download PDF</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</div>

<?php
/**
 * views/sales_summary.php - Aggregated sales totals
 *
 * Requires: $filteredSales, $salesUnits, $salesTotal,
 *           $salesByProduct, $salesByDay,
 *           $salesByRecorderProduct,
 *           $products, $categories,
 *           $reportFrom, $reportTo, $reportProductId, $reportCategory
 */
$filteredSales = $filteredSales ?? [];
$salesUnits = $salesUnits ?? 0;
$salesTotal = $salesTotal ?? 0;
$salesByProduct = $salesByProduct ?? [];
$salesByDay = $salesByDay ?? [];
$salesByRecorderProduct = $salesByRecorderProduct ?? [];
$products = $products ?? [];
$categories = $categories ?? [];
$reportFrom = $reportFrom ?? '';
$reportTo = $reportTo ?? '';
$reportProductId = $reportProductId ?? '';
$reportCategory = $reportCategory ?? '';
?>

<div class="sales-summary-page">
    <section class="sales-summary-filter sales-filter-toolbar" aria-labelledby="sales-summary-filter-title">
        <div class="sales-summary-filter-heading">
            <div>
                <h2 id="sales-summary-filter-title">Filters</h2>
            </div>
        </div>
        <form class="search-form sales-filter-form sales-summary-filter-form" method="GET" action="dashboard.php">
            <input type="hidden" name="view" value="report">

            <div class="sales-filter-field">
                <label for="summary-filter-product">Product</label>
                <select id="summary-filter-product" name="product_id">
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
                <label for="summary-filter-category">Category</label>
                <select id="summary-filter-category" name="category">
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
                <label for="summary-filter-from">From</label>
                <input id="summary-filter-from" type="date" name="from"
                       value="<?php echo htmlspecialchars($reportFrom); ?>">
            </div>

            <div class="sales-filter-field">
                <label for="summary-filter-to">To</label>
                <input id="summary-filter-to" type="date" name="to"
                       value="<?php echo htmlspecialchars($reportTo); ?>">
            </div>

            <div class="sales-filter-actions">
                <button type="submit" class="btn btn-primary">Apply filters</button>
                <?php if ($reportFrom !== '' || $reportTo !== '' || $reportProductId !== '' || $reportCategory !== ''): ?>
                    <a href="dashboard.php?view=report" class="btn btn-ghost">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section class="stats sales-summary-stats" aria-label="Sales overview">
        <div class="stat-card sales-metric-units">
            <div class="sales-summary-metric-content">
                <div class="stat-label">Units Sold</div>
                <div class="stat-value"><?php echo $salesUnits; ?></div>
            </div>
        </div>
        <div class="stat-card sales-metric-revenue">
            <div class="sales-summary-metric-content">
                <div class="stat-label">Revenue (incl. 13% VAT)</div>
                <div class="stat-value">Rs.<?php echo number_format($salesTotal, 2); ?></div>
            </div>
        </div>
        <div class="stat-card sales-metric-transactions">
            <div class="sales-summary-metric-content">
                <div class="stat-label">Transactions</div>
                <div class="stat-value"><?php echo count($filteredSales); ?></div>
            </div>
        </div>
    </section>

    <?php if (empty($filteredSales)): ?>
        <section class="sales-summary-empty">
            <h2>No sales match these filters</h2>
        </section>
    <?php else: ?>
        <div class="sales-summary-grid">
            <section class="table-wrap sales-summary-table sales-summary-product">
                <div class="sales-summary-table-heading">
                    <div>
                        <h2>Sales by Product</h2>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Product-ID</th>
                            <th>Units Sold</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($salesByProduct as $sp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sp['name']); ?></td>
                            <td><?php echo htmlspecialchars($sp['product_id']); ?></td>
                            <td><?php echo $sp['qty']; ?></td>
                            <td>Rs.<?php echo number_format($sp['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <section class="table-wrap sales-summary-table sales-summary-day">
                <div class="sales-summary-table-heading">
                    <div>
                        <h2>Sales by Day</h2>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Units Sold</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($salesByDay as $day => $sd): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($day); ?></td>
                            <td><?php echo $sd['qty']; ?></td>
                            <td>Rs.<?php echo number_format($sd['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>

        <section class="table-wrap sales-summary-table sales-summary-staff">
            <div class="sales-summary-table-heading">
                <div>
                    <h2>Sales by Staff/Admin and Product</h2>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Recorded By</th>
                        <th>Product</th>
                        <th>Product-ID</th>
                        <th>Units Sold</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($salesByRecorderProduct as $recorder => $recorderSales): ?>
                    <?php foreach ($recorderSales as $recorderSale): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($recorder); ?></td>
                            <td><?php echo htmlspecialchars($recorderSale['name']); ?></td>
                            <td><?php echo htmlspecialchars($recorderSale['product_id']); ?></td>
                            <td><?php echo $recorderSale['qty']; ?></td>
                            <td>Rs.<?php echo number_format($recorderSale['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
</div>

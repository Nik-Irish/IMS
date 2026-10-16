<?php

require_once __DIR__ . '/includes/auth.php';
requireRole('staff');

require_once __DIR__ . '/includes/invoice.php';
require_once __DIR__ . '/config/connector.php';
require_once __DIR__ . '/includes/functions.php';

define('DASHBOARD_CONTROLLER', true);
require_once __DIR__ . '/dashboard/helpers.php';

$staffName = $_SESSION['username'];

$allowedViews = ['list', 'sale_add', 'sales'];
$view = $_GET['view'] ?? 'list';

if (!in_array($view, $allowedViews, true)) {
    $view = 'list';
}

$errorMessage = '';
$successMessage = '';

$products = $pdo
    ->query('SELECT * FROM products ORDER BY id')
    ->fetchAll(PDO::FETCH_ASSOC);

$salesStatement = $pdo->prepare(
    'SELECT * FROM sales
     WHERE staff_name = ?
     ORDER BY sale_date DESC, created_at DESC'
);

$salesStatement->execute([$staffName]);
$sales = $salesStatement->fetchAll(PDO::FETCH_ASSOC);

// Allow staff to download only their own invoices.
if (($_GET['download'] ?? '') === 'pdf' && isset($_GET['sale_id'])) {
    $sale = getSale($pdo, (int) $_GET['sale_id']);

    if (!$sale || ($sale['staff_name'] ?? '') !== $staffName) {
        http_response_code(404);
        echo 'Bill not found.';
        exit;
    }

    downloadInvoicePdf($sale);
}

// Process a submitted sale.
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'sale'
) {
    $result = recordSaleFromPost($pdo);

    if ($result['ok']) {
        $successMessage = $result['message'];
        $view = 'sales';
    } else {
        $errorMessage = $result['message'];
        $view = 'sale_add';
    }

    $salesStatement->execute([$staffName]);
    $sales = $salesStatement->fetchAll(PDO::FETCH_ASSOC);
}

$customers = $view === 'sale_add'
    ? getCustomers($pdo)
    : [];

$pageTitles = [
    'list' => 'Dashboard',
    'sale_add' => 'Record Sale',
    'sales' => 'My Sales',
];

$pageSubtitles = [
    'list' => 'Check stock before you sell.',
    'sale_add' => 'Stock and totals update the moment you save.',
    'sales' => 'Every sale you recorded, with PDF receipts.',
];

$dashboardScript = 'Staff_dashboard.php';
$pageTitle = $pageTitles[$view];

require_once __DIR__ . '/staff/includes/staff_header.php';
?>

<?php require __DIR__ . '/views/messages.php'; ?>

<?php
$viewMap = [
    'list' => 'staff/views/staff_products.php',
    'sale_add' => 'views/record_sale.php',
    'sales' => 'staff/views/staff_sales.php',
];

$viewFile = __DIR__ . '/' . $viewMap[$view];

if (file_exists($viewFile)) {
    require_once $viewFile;
}
?>

</main>
</div>
</body>
</html>

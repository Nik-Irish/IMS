<?php
/**
 * views/messages.php - Error/success messages + unread alert banner.
 * Extracted from dashboard.php; included after includes/header.php.
 * Do not open directly - included by dashboard.php.
 *
 * Context variables provided by dashboard.php:
 *
 * @var string     $errorMessage        Rendered as .msg-error
 * @var string     $successMessage      Rendered as .msg-success
 * @var array      $bannerNotes         Unread alerts (dashboard/stats.php)
 * @var int        $unreadNotifications Unread alert count (dashboard/stats.php)
 * @var string     $view                Active view slug
 */

if (!defined('DASHBOARD_CONTROLLER')) {
    http_response_code(403);
    exit('Direct access not allowed.');
}

// defaults so staff pages (no alerts) can include this partial safely
$errorMessage = $errorMessage ?? '';
$successMessage = $successMessage ?? '';
$bannerNotes = $bannerNotes ?? [];
$unreadNotifications = $unreadNotifications ?? 0;
$view = $view ?? '';
$showAlerts = $showAlerts ?? false;
?>
<?php if ($errorMessage !== ''): ?>
    <div class="msg msg-error"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

<?php if ($successMessage !== ''): ?>
    <div class="msg msg-success"><?php echo htmlspecialchars($successMessage); ?></div>
<?php endif; ?>

<!-- View-specific styles (alert banner) -->
<link rel="stylesheet" href="css/banner.css?v=2">

<?php if (!empty($bannerNotes) && $showAlerts && $view !== 'notifications'): ?>
    <div class="notif-banner <?php echo in_array('low_stock', array_column($bannerNotes, 'type'), true) ? 'notif-banner-low-stock' : ''; ?>">
        <div class="notif-banner-title">
            <span class="notif-banner-count"><?php echo $unreadNotifications; ?></span>
            <span><?php echo $unreadNotifications === 1 ? 'Alert needs' : 'Alerts need'; ?> attention</span>
            <a href="dashboard.php?view=notifications">View All Alerts</a>
        </div>
        <ul class="notif-banner-list">
            <?php foreach ($bannerNotes as $bn): ?>
                <?php $isLowStock = ($bn['type'] ?? '') === 'low_stock'; ?>
                <li class="<?php echo $isLowStock ? 'notif-low-stock' : ''; ?>">
                    <span class="notif-item-mark" aria-hidden="true"><?php echo $isLowStock ? '!' : 'i'; ?></span>
                    <span class="notif-item-copy">
                        <strong><?php echo htmlspecialchars($bn['title'] ?? 'Alert'); ?></strong>
                        <span><?php echo htmlspecialchars($bn['message'] ?? ''); ?></span>
                    </span>
                    <?php if (!empty($bn['product_id'])): ?>
                        <a class="notif-product-link" href="dashboard.php?view=inventory&amp;id=<?php echo urlencode($bn['product_id']); ?>">View product</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
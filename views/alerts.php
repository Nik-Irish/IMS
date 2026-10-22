<?php
/**
 * views/alerts.php - System alerts (renamed from Notifications)
 *
 * Requires: $sortedNotifications, $unreadNotifications
 */
?>

<div class="alerts-page">
<div class="toolbar">
    <div class="alert-actions">
        <form method="POST" action="dashboard.php?view=notifications">
            <button type="submit" name="action" value="mark_all_read"
                    class="btn btn-secondary">
                Mark All as Read
            </button>
        </form>
        <form method="POST" action="dashboard.php?view=notifications"
              onsubmit="return confirm('Delete all alerts? This cannot be undone.');">
            <button type="submit" name="action" value="clear_notifications"
                    class="btn btn-danger">
                Delete All Alerts
            </button>
        </form>
    </div>
</div>

<div class="alerts-list">
    <?php if (empty($sortedNotifications)): ?>
        <div class="table-wrap alerts-empty">
            <span class="alerts-empty-mark" aria-hidden="true">✓</span>
            <h2>All clear</h2>
            <p>No alerts right now. Products are above the low-stock limit.</p>
        </div>
    <?php else: ?>
        <?php foreach ($sortedNotifications as $n): $unread = (int)$n['is_read'] === 0; ?>
            <?php $isLowStock = ($n['type'] ?? '') === 'low_stock'; ?>
            <article class="alert-card <?php echo $unread ? 'is-unread' : 'is-read'; ?> <?php echo $isLowStock ? 'alert-card-low-stock' : ''; ?>">
                <div class="alert-card-mark" aria-hidden="true"><?php echo $isLowStock ? '!' : 'i'; ?></div>
                <div class="alert-card-content">
                    <div class="alert-card-heading">
                        <div>
                            <div class="alert-card-tags">
                                <span class="alert-type"><?php echo htmlspecialchars(str_replace('_', ' ', $n['type'] ?? 'info')); ?></span>
                                <span class="alert-status <?php echo $unread ? 'badge-unread' : 'badge-read'; ?>">
                                    <?php echo $unread ? 'Unread' : 'Read'; ?>
                                </span>
                            </div>
                            <h2><?php echo htmlspecialchars($n['title'] ?? 'Alert'); ?></h2>
                        </div>
                        <time class="alert-date">
                            <?php echo !empty($n['created_at'])
                                ? htmlspecialchars(date('Y-m-d H:i', strtotime($n['created_at'])))
                                : '-'; ?>
                        </time>
                    </div>
                    <p class="alert-card-message"><?php echo htmlspecialchars($n['message'] ?? ''); ?></p>
                    <div class="alert-card-footer">
                        <?php if (!empty($n['product_id'])): ?>
                            <a class="alert-product-link" href="dashboard.php?view=inventory&amp;id=<?php echo urlencode($n['product_id']); ?>">
                                View product
                            </a>
                        <?php endif; ?>
                        <div class="alert-card-actions">
                            <?php if ($unread): ?>
                                <form method="POST">
                                    <input type="hidden" name="action" value="mark_read">
                                    <input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-secondary">Mark as read</button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" onsubmit="return confirm('Delete this alert?');">
                                <input type="hidden" name="action" value="delete_notification">
                                <input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</div>
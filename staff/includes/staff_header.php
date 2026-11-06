<?php
/**
 * includes/staff_header.php
 * Header for the staff dashboard.
 */
$pageTitle = $pageTitle ?? 'Staff Dashboard';
$pageSub = $pageSub ?? [];
$view = $view ?? 'list';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?> | IMS Nepal Staff</title>
    <link rel="stylesheet" href="css/app.css?v=1">
    <script src="assets/js/dashboard.js?v=1" defer></script>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="Staff_dashboard.php?view=list" class="brand brand-link">IMS Nepal</a>
        </div>
        <nav class="nav" id="primary-navigation" aria-label="Main navigation">
            <a href="Staff_dashboard.php?view=list"
               class="nav-link <?php echo $view === 'list' ? 'active' : ''; ?>">
                Dashboard
            </a>
            <a href="Staff_dashboard.php?view=sale_add"
               class="nav-link <?php echo $view === 'sale_add' ? 'active' : ''; ?>">
                Record Sale
            </a>
            <a href="Staff_dashboard.php?view=sales"
               class="nav-link <?php echo $view === 'sales' ? 'active' : ''; ?>">
                My Sales
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="signin.php?action=logout" class="nav-link logout" style="justify-content: center;">Logout</a>
            <a href="index.php" class="nav-link back-to-site" style="justify-content: center;">← Back to website</a>
        </div>
    </aside>
    <main class="main">
        <header class="topbar">
            <div>
                <h1>
                    <?php echo $view === 'list'
                        ? 'Hey, ' . htmlspecialchars($_SESSION['username'] ?? 'Staff')
                        : htmlspecialchars($pageTitle); ?>
                </h1>
                <p class="topbar-sub">
                    <?php echo htmlspecialchars($pageSub[$view] ?? ''); ?>
                </p>
            </div>
        </header>

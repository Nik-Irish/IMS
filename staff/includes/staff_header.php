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
    <link rel="stylesheet" href="css/base.css?v=1">
    <link rel="stylesheet" href="css/sidebar.css?v=3">
    <link rel="stylesheet" href="css/components.css?v=1">
    <link rel="stylesheet" href="css/theme.css?v=26">
    <link rel="stylesheet" href="css/products.css?v=7">
    <script src="assets/js/sidebar-toggle.js?v=1" defer></script>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="Staff_dashboard.php?view=list" class="brand brand-link">IMS Nepal</a>
            <button class="sidebar-toggle" type="button" aria-label="Hide navigation"
                    aria-controls="primary-navigation" aria-expanded="true" title="Hide navigation">
                <span aria-hidden="true">‹</span>
            </button>
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
            <a href="login.php?action=logout" class="nav-link logout">Logout</a>
        </div>
    </aside>
    <main class="main">
        <header class="topbar">
            <button class="sidebar-toggle sidebar-toggle-show" type="button" aria-label="Show navigation"
                    aria-controls="primary-navigation" aria-expanded="false" title="Show navigation" hidden>
                <span aria-hidden="true">☰</span>
            </button>
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

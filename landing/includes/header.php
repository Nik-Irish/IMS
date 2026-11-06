<?php
$pages = [
    'home' => ['label' => 'Home', 'href' => 'index.php'],
    'about' => ['label' => 'About', 'href' => 'about.php'],
    'contact' => ['label' => 'Contact', 'href' => 'contact.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="theme-color" content="#F5F6F2">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="css/landing.css?v=1">
</head>
<body<?php echo $bodyClass !== '' ? ' class="' . htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
    <header>
        <div class="product-header">
            <div class="product-header-inner">
                <a class="wordmark" href="index.php" aria-label="IMS Nepal home">
                    <span>IMS <span class="wordmark-light">Nepal</span></span>
                </a>
                <nav class="site-nav" id="site-nav" aria-label="IMS Nepal">
                    <?php foreach ($pages as $key => $page): ?>
                        <a href="<?php echo $page['href']; ?>"<?php echo $currentPage === $key ? ' aria-current="page"' : ''; ?>><?php echo $page['label']; ?></a>
                    <?php endforeach; ?>
                </nav>
                <div class="header-actions">
                    <a class="sign-in-link" href="../signin.php">Sign in</a>
                    <a class="button button-small" href="../signin.php?action=register">Create account</a>
                </div>
            </div>
        </div>
    </header>

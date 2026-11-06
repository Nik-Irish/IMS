<?php
$footerLinks = $currentPage === 'home'
    ? [
        ['href' => '#features', 'label' => 'Features'],
        ['href' => 'about.php', 'label' => 'About'],
        ['href' => 'contact.php', 'label' => 'Contact'],
    ]
    : [
        ['href' => 'index.php', 'label' => 'Home'],
        ['href' => 'about.php', 'label' => 'About'],
        ['href' => 'contact.php', 'label' => 'Contact'],
    ];
?>
    <footer class="site-footer<?php echo $currentPage === 'contact' ? ' contact-footer' : ''; ?>">
        <div class="footer-brand">
            <a class="wordmark footer-wordmark" href="index.php">IMS Nepal</a>
            <p>Products, stock, sales, and reports in one place.</p>
        </div>
        <?php if ($currentPage !== 'contact'): ?>
            <div class="footer-contact">
                <span class="footer-contact-title">CONTACT</span>
                <a href="tel:+9779842020363"><?php echo $currentPage === 'home' ? 'Phone: ' : ''; ?>+977 9842020363</a>
                <a href="mailto:sales@IMS.com"><?php echo $currentPage === 'home' ? 'Email: ' : ''; ?>sales@IMS.com</a>
            </div>
        <?php endif; ?>
        <div class="footer-links">
            <?php foreach ($footerLinks as $link): ?>
                <a href="<?php echo $link['href']; ?>"><?php echo $link['label']; ?></a>
            <?php endforeach; ?>
            <a href="../signin.php">Sign in</a>
        </div>
    </footer>
</body>
</html>

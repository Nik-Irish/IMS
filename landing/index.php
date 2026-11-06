<?php
$pageTitle = 'IMS Nepal | Inventory and Sales Management';
$currentPage = 'home';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>

    <main>
        <section class="hero-band">
            <div class="hero">
                <div class="hero-copy">
                    <p class="eyebrow"><span class="eyebrow-mark"></span> INVENTORY AND SALES FOR YOUR BUSINESS</p>
                    <h1>Manage stock and sales in one place.</h1>
                    <p class="hero-description">
                        Add products, record sales, and check stock levels from the same system.
                        No more keeping separate lists for day-to-day sales.
                    </p>
                    <div class="hero-actions">
                        <a class="button" href="../signin.php?action=register">Create an account <span aria-hidden="true">→</span></a>
                        <a class="button button-secondary" href="#features">See what IMS does</a>
                    </div>
                    <p class="hero-note"><span aria-hidden="true">✓</span> Stock updates when you record a sale.</p>
                </div>

                <figure class="hero-visual hero-visual-home">
                    <img src="assets/retail-checkout.jpg" alt="A shop owner helping a customer at checkout">
                    <figcaption class="hero-visual-card">
                        <span class="visual-card-label">WHEN YOU RECORD A SALE</span>
                        <span class="visual-card-row"><span class="visual-card-icon" aria-hidden="true">✓</span>Stock quantity is updated</span>
                        <span class="visual-card-row"><span class="visual-card-icon" aria-hidden="true">✓</span>The sale appears in your reports</span>
                    </figcaption>
                </figure>
            </div>
        </section>

        <section class="workflow-strip" aria-label="Products, stock, sales, and reports">
            <p>KEEP TRACK OF</p>
            <div><span>Products</span><i></i><span>Stock</span><i></i><span>Sales</span><i></i><span>Reports</span></div>
        </section>

        <section class="feature-section" id="features">
            <div class="section-heading">
                <p class="eyebrow"><span class="eyebrow-mark"></span> WHAT YOU CAN DO</p>
                <h2>The tools you need for everyday sales.</h2>
                <p class="section-intro">Add products, record each sale, and check how much stock is left.</p>
            </div>
            <div class="feature-grid">
                <article class="feature-card" id="inventory">
                    <span class="feature-index">INVENTORY</span>
                    <h3>Add products and track stock</h3>
                    <p>Keep product names, IDs, prices, and quantities together. Stock changes as you update inventory or record sales.</p>
                    <a href="about.php#stock">Learn about stock tracking <span aria-hidden="true">→</span></a>
                </article>
                <article class="feature-card" id="sales">
                    <span class="feature-index">SALES</span>
                    <h3>Record sales and print bills</h3>
                    <p>Save the sale and customer details, update stock, and print a bill with the 13% VAT breakdown.</p>
                    <a href="about.php#sales">Learn about sales <span aria-hidden="true">→</span></a>
                </article>
                <article class="feature-card" id="reports">
                    <span class="feature-index">REPORTS AND ALERTS</span>
                    <h3>See what needs attention</h3>
                    <p>Review sales by date, product, or staff member. Get an alert when a product has 10 units or fewer.</p>
                    <a href="about.php#reports">Learn about reports <span aria-hidden="true">→</span></a>
                </article>
            </div>
        </section>

        <section class="home-cta">
            <div>
                <p class="eyebrow">GETTING STARTED</p>
                <h2>Want to keep your stock and sales in one place?</h2>
            </div>
            <a class="button button-light" href="../signin.php?action=register">Create an account <span aria-hidden="true">→</span></a>
        </section>

    </main>

<?php require __DIR__ . '/includes/footer.php'; ?>

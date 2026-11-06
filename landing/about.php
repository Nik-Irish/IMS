<?php
$pageTitle = 'About IMS Nepal';
$currentPage = 'about';
$bodyClass = 'info-page about-page';
require __DIR__ . '/includes/header.php';
?>

    <main>
        <section class="info-hero about-hero">
            <div class="info-hero-copy">
                <p class="eyebrow"><span class="eyebrow-mark"></span> ABOUT IMS NEPAL</p>
                <h1>Keep products, stock, and sales records together.</h1>
                <p>IMS Nepal gives shop owners and staff one place to manage products and record sales. When a sale is saved, stock is updated. Owners can check invoices, low-stock alerts, and sales reports from the admin dashboard.</p>
                <a class="text-link" href="contact.php">Questions? Contact us <span aria-hidden="true">→</span></a>
            </div>
            <figure class="info-hero-visual">
                <img src="assets/warehouse-login.jpg" alt="Organized shelves of products in a warehouse">
                <figcaption><span>PRODUCTS TO SALES</span> Keep the records together.</figcaption>
            </figure>
        </section>

        <section class="about-pillars" aria-labelledby="about-pillars-title">
            <div class="section-heading">
                <p class="eyebrow"><span class="eyebrow-mark"></span> HOW IT WORKS</p>
                <h2 id="about-pillars-title">Follow the work from stock to sale.</h2>
            </div>
            <div class="pillar-grid">
                <article class="pillar-card" id="stock">
                    <span class="pillar-icon" aria-hidden="true">✓</span>
                    <h3>Manage products and stock</h3>
                    <p>Store each product’s name, ID, category, price, and quantity. Update stock when deliveries arrive or quantities change.</p>
                </article>
                <article class="pillar-card" id="sales">
                    <span class="pillar-icon" aria-hidden="true">✓</span>
                    <h3>Record sales and print bills</h3>
                    <p>Record the product, quantity, customer, and staff member. Stock goes down automatically, and you can print a bill with 13% VAT.</p>
                </article>
                <article class="pillar-card" id="reports">
                    <span class="pillar-icon" aria-hidden="true">✓</span>
                    <h3>Check reports and alerts</h3>
                    <p>Review sales by date, product, or staff member. Low-stock alerts flag products with 10 units or fewer.</p>
                </article>
            </div>
        </section>

        <section class="about-bottom">
            <p class="eyebrow">WANT TO KNOW MORE?</p>
            <h2>Have a question about how IMS works?</h2>
            <a class="button button-light" href="contact.php">Contact us <span aria-hidden="true">→</span></a>
        </section>
    </main>

<?php require __DIR__ . '/includes/footer.php'; ?>

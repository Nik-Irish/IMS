<?php
$pageTitle = 'Contact IMS Nepal';
$currentPage = 'contact';
$bodyClass = 'info-page contact-page';
require __DIR__ . '/includes/header.php';
?>

    <main class="contact-page-main">
        <section class="contact-hero">
            <div class="contact-intro">
                <p class="eyebrow"><span class="eyebrow-mark"></span> CONTACT IMS NEPAL</p>
                <h1>Questions? We’re happy to help.</h1>
                <p>Call or email us about setting up IMS Nepal, managing your stock, or recording sales. We’ll be glad to hear from you.</p>
            </div>

            <section class="contact-details" aria-labelledby="contact-details-title">
                <p class="contact-card-kicker">CALL OR EMAIL</p>
                <h2 id="contact-details-title">Contact details</h2>
                <p class="contact-card-description">Choose whichever is easier for you.</p>
                <a class="contact-method" href="tel:+9779842020363">
                    <span class="contact-method-icon" aria-hidden="true">☎</span>
                    <span><small>CALL US</small><strong>+977 9842020363</strong></span>
                    <span class="contact-method-arrow" aria-hidden="true">↗</span>
                </a>
                <a class="contact-method" href="mailto:sales@IMS.com">
                    <span class="contact-method-icon" aria-hidden="true">@</span>
                    <span><small>EMAIL US</small><strong>sales@IMS.com</strong></span>
                    <span class="contact-method-arrow" aria-hidden="true">↗</span>
                </a>
                <p class="contact-card-note">We’ll be glad to answer your questions.</p>
            </section>
        </section>
    </main>

<?php require __DIR__ . '/includes/footer.php'; ?>

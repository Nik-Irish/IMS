# Inventory Management System (IMS Nepal)

A PHP and MySQL web application for tracking products, stock movements, sales, staff accounts, and low-stock alerts. Administrators manage the catalog and staff; staff can view inventory and record sales.

## Features

- **Role-based access:** separate administrator and staff dashboards.
- **Product catalog:** add, search, and edit products using unique Product-ID codes. Adding a product with an existing Product-ID adds stock to that product.
- **Inventory tracking:** stock-in, stock-out, manual quantity adjustments, and a per-product movement history.
- **Sales:** record one product per sale, customer details, and an optional note. Sales reduce stock and are recorded with a unique bill number.
- **VAT and receipts:** totals include 13% VAT. Receipts open a print dialog, where they can be printed or saved as PDF.
- **Reports:** filter sales by date, product, and category; view sales summaries by product, staff member, and day.
- **Staff management:** administrators can create, edit, and delete staff accounts.
- **Alerts:** low-stock and out-of-stock notifications, plus a product-catalog milestone alert.
- **Password recovery:** email one-time codes expire after two minutes; mail delivery requires a configured PHP sendmail relay.

## Requirements

- PHP 7.4 or newer, with **PDO MySQL** enabled.
- MySQL or a compatible MariaDB server, with InnoDB support.
- Apache or another PHP-capable web server.
- For password recovery by email: a working sendmail-compatible relay configured for PHP.

The project has no Composer or frontend package-manager dependencies.

## Installation

### XAMPP on Windows

1. Copy or clone the project into XAMPP's web root, for example `C:\xampp\htdocs\IMS`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Check the database settings in both:
   - `config/connector.php` — application database connection.
   - `install.php` — installer connection and database name.

   The defaults are MySQL on `localhost:3306`, user `root`, an empty password, and database `ims`. Change them to match your local setup. The configured database user must be allowed to create the database and tables.
4. Open `http://localhost/IMS/install.php` in your browser. The installer creates the database and tables, applies supported schema upgrades, adds foreign keys, and creates or resets the administrator account.
5. Sign in at `http://localhost/IMS/login.php`.

### Initial administrator login

- **Username:** `admin`
- **Password:** `Password123$`

Change this password immediately. Configure the administrator email in `install.php` to the address you can access if you plan to use password recovery.

> **Important:** `install.php` resets the existing `admin` account's password to `Password123$` every time the installer is run. After a successful installation, remove or restrict access to `install.php`. Do not leave the installer publicly accessible.

### Manual database setup

The recommended setup is the web installer above, which also handles migrations and checks before adding foreign keys. If setting up a new database manually, create/select the `ims` database and run:

1. `sql/01_schema.sql`
2. `sql/02_foreign_keys.sql`

`sql/03_migrations.sql` contains upgrade statements for older databases. Do **not** run the entire file as a single script: the installer checks each migration's preconditions and fills in placeholders at runtime.

## Configuration

### Database

Update the connection values in `config/connector.php` and the installer values in `install.php` so they agree:

| Setting | Default |
| --- | --- |
| Host | `localhost` |
| Port | `3306` |
| Username | `root` |
| Password | Empty |
| Database | `ims` |

For any non-local deployment, use a dedicated database account with only the permissions the application needs; do not use an unrestricted root account.

### Password reset email

The application sends reset email through the sendmail executable configured by PHP's `sendmail_path`. Configure the relay (including any SMTP credentials) in your PHP/XAMPP mail settings and verify that the configured executable exists. The sender address and display name are currently set in `includes/Mailer.php`. Email recovery is optional; login and other application functions do not require it.

## Using the application

### Administrator

After signing in with an administrator account, use the dashboard to:

- Add products and manage prices, descriptions, categories, and quantities.
- Search the product list and inspect product movement and sales histories.
- Add or remove stock and review low-stock alerts.
- Record sales, review transactions, apply report filters, and print/save receipts.
- Review sales summaries and manage staff accounts.

Stock is considered low at **10 units or fewer**. A sale records a stock movement and deducts its quantity as part of the sale transaction.

### Staff

Staff can view current products and stock, record sales, review their own sales, and print/save their own receipts. Staff accounts can be created through the registration page or by an administrator. New self-registered accounts have the staff role.

## Project structure

```text
.
├── config/
│   └── connector.php          # PDO connection to MySQL
├── css/                       # Application stylesheets
├── dashboard/                 # Administrator dashboard logic and actions
├── includes/                  # Authentication, shared helpers, mail, invoices
├── install/                   # Installer helpers and schema setup
├── login/                     # Login, registration, and password recovery
├── sql/                       # Base schema, foreign keys, migrations
├── staff/                     # Staff dashboard views and layout
├── views/                     # Shared administrator product and sales views
├── dashboard.php              # Administrator entry point (admin role)
├── staff_dashboard.php        # Staff entry point (staff role)
├── login.php                  # Login and account recovery entry point
└── install.php                # Database installer; restrict after use
```

## Database tables

The installer creates these tables:

- `users` — login credentials, roles, and staff account data.
- `products` — product details, Product-ID, price, and current quantity.
- `movements` — stock-in, stock-out, sale, and adjustment history.
- `sales` — bill, product snapshot, quantity, totals, customer, date, and staff.
- `notifications` — stock and catalog alerts.
- `otps` — hashed, expiring password-reset codes.

## Troubleshooting

- **Database connection error:** make sure MySQL is running and that the host, port, username, password, and database settings match in `config/connector.php` and `install.php`.
- **Installer cannot create the database:** grant the configured MySQL account permission to create databases and tables, or create the database in advance and update the application configuration as appropriate.
- **Password-reset email does not arrive:** confirm the PHP `sendmail_path`, relay executable, SMTP configuration, and sender settings. Check the PHP error log for mail-delivery errors.
- **A product cannot be deleted:** products referenced by sales or stock movements are protected by database foreign keys. Keep the product record so its history remains consistent.

## Development and tests

There is currently no automated test suite or build command configured in this repository. The app can be run locally with XAMPP or another PHP/MySQL environment using the installation steps above.

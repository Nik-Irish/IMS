# IMS Nepal

PHP/MySQL inventory and sales application with separate administrator and staff access.

## At a glance

| Area | Capability |
| --- | --- |
| Inventory | Product catalog, stock adjustments, movement history, low-stock alerts |
| Sales | Record sales, deduct stock, generate bills and printable receipts |
| Reporting | Filter sales and summarize by product, staff member, and day |
| Accounts | Admin-managed staff, self-registration, email-based password recovery |

**Business rules:** VAT is 13%; low stock is 10 units or fewer; each sale records one product and a unique bill number.

## Requirements

- PHP 7.4+ with PDO MySQL
- MySQL or MariaDB with InnoDB
- Apache or another PHP-capable web server
- Optional: PHP sendmail-compatible relay for password recovery

No Composer or frontend package-manager dependencies.

## Run locally (XAMPP)

1. Place the project at `C:\xampp\htdocs\IMS`.
2. Start Apache and MySQL.
3. Set matching database credentials in `config/connector.php` and `install.php`.
4. Open `http://localhost/IMS/install.php`.
5. Sign in at `http://localhost/IMS/login.php`.

Default database: `ims` on `localhost:3306`, user `root`, empty password. The installer needs permission to create the database and tables.

### Initial administrator

| Username | Password |
| --- | --- |
| `admin` | `Password123$` |

Change the password after first sign-in. **The installer resets the admin password whenever it runs. Remove or restrict `install.php` after setup.**

## Configuration

| Setting | Location | Notes |
| --- | --- | --- |
| Database connection | `config/connector.php`, `install.php` | Keep values consistent; use a dedicated database account outside local development. |
| Password-reset email | PHP `sendmail_path`, `includes/Mailer.php` | Configure a sendmail-compatible relay and sender details. Email is optional. |

## Database

| Table | Data |
| --- | --- |
| `users` | Credentials, roles, staff accounts |
| `products` | Product details, price, quantity |
| `movements` | Stock-in, stock-out, sale, adjustment history |
| `sales` | Bill, product snapshot, quantity, totals, customer, staff |
| `notifications` | Stock and catalog alerts |
| `otps` | Hashed, expiring password-reset codes |

For manual setup of a new database, run `sql/01_schema.sql`, then `sql/02_foreign_keys.sql`. `sql/03_migrations.sql` is for upgrades; the installer applies migrations with precondition checks.

## Project map

```text
config/       Database connection
assets/       Local images
css/          Stylesheets
dashboard/    Administrator dashboard and actions
includes/     Authentication, mail, shared helpers, invoices
install/      Installer helpers
login/        Login, registration, password recovery
sql/          Schema, foreign keys, migrations
staff/        Staff dashboard and views
views/        Shared administrator views
dashboard.php Administrator entry point
staff_dashboard.php
login.php     Authentication entry point
install.php   Database installer; restrict after use
```

The warehouse login image is stored locally; the page does not fetch it at runtime.

## Known limitations

- Each sale contains one product.
- Newly self-registered accounts use the staff role.
- Email recovery depends on server mail configuration.
- No automated test suite or build command is configured.

## Troubleshooting

| Symptom | Check |
| --- | --- |
| Database connection fails | MySQL status and matching credentials in `config/connector.php` and `install.php`. |
| Installer cannot create database | Grant database-creation permissions or create the database first. |
| Reset email is missing | PHP `sendmail_path`, relay/SMTP settings, sender settings, and PHP error log. |
| Product deletion is blocked | Sales and movement records reference the product; retain it to preserve history. |

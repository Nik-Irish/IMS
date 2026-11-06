# IMS Nepal

A PHP/MySQL inventory and sales application with separate administrator and staff access.

---

## At a Glance

| Area | Capability |
|------|------------|
| **Inventory** | Product catalog, stock adjustments, movement history, low-stock alerts |
| **Sales** | Record sales, deduct stock, generate bills and printable receipts |
| **Reporting** | Filter sales and summarize by product, staff member, and day |
| **Accounts** | Admin-managed staff, self-registration, email-based password recovery |

**Business rules:** VAT is 13%; low stock is 10 units or fewer; each sale records one product and a unique bill number.

---

## Requirements

- PHP 7.4+ with PDO MySQL
- MySQL or MariaDB with InnoDB
- Apache or another PHP-capable web server
- Optional: PHP sendmail-compatible relay for password recovery

*No Composer or frontend package-manager dependencies.*

---

## Run Locally (XAMPP)

1. Place the project at `C:\xampp\htdocs\IMS`.
2. Start Apache and MySQL.
3. Configure the database connection in `config/connector.php`.
4. Ensure the `ims` database is already initialized with the project schema and an administrator account.
5. Open [http://localhost/IMS/login.php](http://localhost/IMS/login.php) to sign in.

*The default connection settings target database `ims` on `localhost:3306`, user `root`, with an empty password. The application does not create or reset the database or administrator account.*

---

## Configuration

| Setting | Location | Notes |
|---------|----------|-------|
| Database connection | `config/connector.php` | Use a dedicated database account outside local development. |
| Password-reset email | PHP `sendmail_path`, `includes/Mailer.php` | Configure a sendmail-compatible relay and sender details. Email is optional. |

---

## Database

| Table | Data |
|-------|------|
| `users` | Credentials, roles, staff accounts |
| `products` | Product details, price, quantity |
| `movements` | Stock-in, stock-out, sale, adjustment history |
| `sales` | Bill, product snapshot, quantity, totals, customer, staff |
| `notifications` | Stock and catalog alerts |
| `otps` | Hashed, expiring password-reset codes |

The SQL files are provided for database administration:
- `sql/01_schema.sql` defines the tables
- `sql/02_foreign_keys.sql` defines their relationships
- `sql/03_migrations.sql` contains upgrade statements (requires review against the current database before use)

*The application does not run these scripts automatically.*

---

## Project Structure

```text
config/           Database connection
assets/           Local images
css/              Stylesheets
dashboard/        Administrator dashboard and actions
includes/         Authentication, mail, shared helpers, invoices
pages/            Public About and Contact pages
login/            Login, registration, password recovery
sql/              Schema, foreign keys, migrations
staff/            Staff dashboard and views
views/            Shared administrator views
dashboard.php     Administrator entry point
staff_dashboard.php
login.php         Authentication entry point
install.php       Product landing page (kept at project root)
```

*The warehouse login image and Unsplash retail photo used on the public landing page are stored locally in `assets/`; the pages do not fetch images from remote hosts at runtime.*

---

## Known Limitations

- Each sale contains one product.
- Newly self-registered accounts use the staff role.
- Email recovery depends on server mail configuration.
- No automated test suite or build command is configured.

---

## Troubleshooting

| Symptom | Check |
|---------|-------|
| Database connection fails | MySQL status and matching credentials in `config/connector.php`. |
| Reset email is missing | PHP `sendmail_path`, relay/SMTP settings, sender settings, and PHP error log. |
| Product deletion is blocked | Sales and movement records reference the product; retain it to preserve history. |

---

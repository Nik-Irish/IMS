-- =============================================================
-- IMS Nepal - 03_migrations.sql
-- Upgrade commands for databases created by an older version.
-- install.php (install/migrations.php) runs each statement ONLY
-- when the target database is missing it. The [key] markers tell
-- the installer which condition guards which statement.
-- Statements marked {PLACEHOLDER} get values filled in at runtime.
-- =============================================================

-- [users.role]
ALTER TABLE users ADD COLUMN role ENUM('admin','staff') NOT NULL DEFAULT 'staff' AFTER password_hash;

-- [users.email]
ALTER TABLE users ADD COLUMN email VARCHAR(150) NULL AFTER username, ADD UNIQUE KEY uq_users_email (email);

-- [users.staff_number]
ALTER TABLE users ADD COLUMN staff_number INT UNSIGNED NULL AFTER role, ADD KEY idx_users_staff_number (staff_number);

-- [sales.staff_name]
ALTER TABLE sales ADD COLUMN staff_name VARCHAR(100) NULL AFTER sale_date;

-- [products.drop_sku]
ALTER TABLE products DROP COLUMN sku;

-- [products.product_id]
ALTER TABLE products ADD COLUMN product_id VARCHAR(50) NOT NULL AFTER name;

-- [products.product_id_backfill]
UPDATE products SET product_id = CONCAT('P', id) WHERE product_id = '';

-- [products.uq_product_id]
ALTER TABLE products ADD UNIQUE KEY uq_products_product_id (product_id);

-- [sales.product_id_from_legacy_sku]
UPDATE sales s
JOIN products p ON p.product_id = s.product_sku
SET s.product_id = p.product_id
WHERE (s.product_id IS NULL OR s.product_id = '') AND s.product_sku <> '';

-- [sales.drop_product_sku]
ALTER TABLE sales DROP COLUMN product_sku;

-- [convert.drop_fk]
ALTER TABLE `{TABLE}` DROP FOREIGN KEY `{FK}`;

-- [convert.update]
UPDATE `{TABLE}` t JOIN products p ON t.product_id = p.id SET t.product_id = p.product_id;

-- [convert.modify]
ALTER TABLE `{TABLE}` MODIFY product_id VARCHAR(50) {NULLABILITY};

<?php
/**
 * install/migrations.php - schema upgrades for older installs.
 * The statements live in sql/03_migrations.sql (keyed); this file decides
 * WHICH statements run against the target database. Included by install.php
 * (which defines INSTALL_APP). Do not open directly.
 */

if (!defined('INSTALL_APP')) {
    http_response_code(403);
    exit('Direct access not allowed.');
}

function migrateSchema(PDO $pdo): array
{
    $messages = [];
    $sql = sqlStatementsByKey('03_migrations.sql');

    // add columns needed by older installs, if missing
    if (!columnExists($pdo, 'users', 'role')) {
        $pdo->exec($sql['users.role']);
        $messages[] = 'Added role column to users.';
    }
    if (!columnExists($pdo, 'users', 'email')) {
        $pdo->exec($sql['users.email']);
        $messages[] = 'Added email column to users.';
    }
    if (!columnExists($pdo, 'users', 'staff_number')) {
        $pdo->exec($sql['users.staff_number']);
        $messages[] = 'Added staff_number column to users.';
    }
    $pdo->exec("UPDATE users SET staff_number = NULL WHERE role <> 'staff'");
    $staffIds = $pdo->query(
        "SELECT id FROM users WHERE role = 'staff' ORDER BY id"
    )->fetchAll(PDO::FETCH_COLUMN);
    $updateStaffNumber = $pdo->prepare(
        'UPDATE users SET staff_number = ? WHERE id = ? AND role = \'staff\''
    );
    foreach ($staffIds as $index => $staffId) {
        $updateStaffNumber->execute([$index + 1, $staffId]);
    }
    if (!columnExists($pdo, 'sales', 'staff_name')) {
        $pdo->exec($sql['sales.staff_name']);
        $messages[] = 'Added staff_name to sales.';
    }
    if (columnExists($pdo, 'products', 'sku')) {
        $pdo->exec($sql['products.drop_sku']);
        $messages[] = 'Removed sku column from products.';
    }
    // give existing rows a unique placeholder Product-ID so the UNIQUE key can be added
    if (!columnExists($pdo, 'products', 'product_id')) {
        $pdo->exec($sql['products.product_id']);
        $pdo->exec($sql['products.product_id_backfill']);
        $messages[] = 'Added product_id column to products.';
    }
    if (!indexExists($pdo, 'products', 'uq_products_product_id')) {
        $pdo->exec($sql['products.uq_product_id']);
        $messages[] = 'Added unique key on products.product_id.';
    }
    // older installs: convert numeric product references to Product-ID codes
    foreach (['movements', 'sales', 'notifications'] as $table) {
        $colType = $pdo->query(
            "SELECT DATA_TYPE FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table' AND COLUMN_NAME = 'product_id'"
        )->fetchColumn();
        if (!$colType || $colType === 'varchar' || $colType === 'char') continue;

        $oldFk = $pdo->query(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table'
               AND COLUMN_NAME = 'product_id' AND REFERENCED_TABLE_NAME = 'products'"
        )->fetchColumn();
        if ($oldFk) {
            $pdo->exec(str_replace(['{TABLE}', '{FK}'], [$table, $oldFk], $sql['convert.drop_fk']));
        }
        $pdo->exec(str_replace('{TABLE}', $table, $sql['convert.update']));
        $nullability = ($table === 'movements') ? 'NOT NULL' : 'NULL';
        $pdo->exec(str_replace(['{TABLE}', '{NULLABILITY}'], [$table, $nullability], $sql['convert.modify']));
        $messages[] = "Converted $table.product_id to store Product-ID codes.";
    }

    if (columnExists($pdo, 'sales', 'product_sku')) {
        $pdo->exec($sql['sales.product_id_from_legacy_sku']);

        $skuForeignKeys = $pdo->query(
            "SELECT DISTINCT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sales'
               AND COLUMN_NAME = 'product_sku' AND REFERENCED_TABLE_NAME IS NOT NULL"
        )->fetchAll(PDO::FETCH_COLUMN);
        foreach ($skuForeignKeys as $foreignKey) {
            $quotedForeignKey = str_replace('`', '``', $foreignKey);
            $pdo->exec("ALTER TABLE sales DROP FOREIGN KEY `$quotedForeignKey`");
        }

        $pdo->exec($sql['sales.drop_product_sku']);
        $messages[] = 'Removed duplicate sales.product_sku column; sales.product_id is retained.';
    }

    return $messages;
}

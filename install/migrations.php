<?php
/**
 * install/migrations.php - schema upgrades for older installs.
 * The statements live in sql/03_migrations.sql (keyed); this file decides
 * WHICH statements run against the target database. Included by installer
 * or dashboard migration code. Do not open directly.
 */

if (!defined('INSTALL_APP') && !defined('DASHBOARD_CONTROLLER')) {
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
    if (!columnExists($pdo, 'products', 'product_id')) {
        $pdo->exec($sql['products.product_id']);
        $messages[] = 'Added product_id column to products.';
    }
    if (!columnExists($pdo, 'sales', 'product_id')) {
        $pdo->exec($sql['sales.product_id']);
        $messages[] = 'Added product_id column to sales.';
    }
    if (columnExists($pdo, 'products', 'sku')) {
        $pdo->exec($sql['products.product_id_from_sku']);
        $messages[] = 'Copied existing product SKUs to product_id where needed.';
    }
    $pdo->exec($sql['products.product_id_backfill']);
    $pdo->exec($sql['products.product_id_not_null']);
    $hasUniqueProductId = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.STATISTICS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products'
           AND COLUMN_NAME = 'product_id' AND NON_UNIQUE = 0
           AND INDEX_NAME IN (
               SELECT INDEX_NAME FROM information_schema.STATISTICS
               WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products'
               GROUP BY INDEX_NAME HAVING COUNT(*) = 1
           )"
    )->fetchColumn();
    if (!(int)$hasUniqueProductId) {
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

        $orphanCount = $pdo->query(
            "SELECT COUNT(*) FROM `$table` t
             LEFT JOIN products p ON t.product_id = p.id
             WHERE t.product_id IS NOT NULL AND p.id IS NULL"
        )->fetchColumn();
        if ((int)$orphanCount > 0) {
            throw new RuntimeException(
                "Cannot convert $table.product_id: $orphanCount values do not match a products.id."
            );
        }

        $oldFks = $pdo->query(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table'
               AND COLUMN_NAME = 'product_id' AND REFERENCED_TABLE_NAME IS NOT NULL"
        )->fetchAll(PDO::FETCH_COLUMN);
        foreach ($oldFks as $oldFk) {
            $quotedForeignKey = str_replace('`', '``', $oldFk);
            $pdo->exec(str_replace(
                ['{TABLE}', '{FK}'],
                [$table, $quotedForeignKey],
                $sql['convert.drop_fk']
            ));
        }
        $nullability = ($table === 'movements') ? 'NOT NULL' : 'NULL';
        $pdo->exec(str_replace(['{TABLE}', '{NULLABILITY}'], [$table, $nullability], $sql['convert.modify']));
        $pdo->exec(str_replace('{TABLE}', $table, $sql['convert.update']));
        $messages[] = "Converted $table.product_id to store Product-ID codes.";
    }

    if (columnExists($pdo, 'sales', 'product_sku')) {
        $mappingStatement = columnExists($pdo, 'products', 'sku')
            ? $sql['sales.product_id_from_products_sku']
            : $sql['sales.product_id_from_legacy_sku'];
        $pdo->exec($mappingStatement);

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

    if (columnExists($pdo, 'products', 'sku')) {
        $skuReferences = $pdo->query(
            "SELECT TABLE_NAME, CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME = 'products'
               AND REFERENCED_COLUMN_NAME = 'sku'"
        )->fetchAll(PDO::FETCH_ASSOC);
        if ($skuReferences) {
            throw new RuntimeException(
                'Cannot remove products.sku while foreign keys still reference it.'
            );
        }
        $pdo->exec($sql['products.drop_sku']);
        $messages[] = 'Removed legacy products.sku column.';
    }

    return $messages;
}

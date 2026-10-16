<?php

define('INSTALL_APP', true);

require __DIR__ . '/install/helpers.php';
require __DIR__ . '/install/schema.php';
require __DIR__ . '/install/migrations.php';
require __DIR__ . '/install/foreign_keys.php';
require __DIR__ . '/install/admin.php';

$host = 'localhost';
$port = 3306;
$user = 'root';
$password = '';
$database = 'ims';
$adminEmail = 'itsnikrish@gmail.com';

$messages = [];
$installationSucceeded = true;

try {
    $pdo = dbConnect($host, $port, $user, $password);

    $messages[] = ensureDatabase($pdo, $database);
    $messages = array_merge(
        $messages,
        createTables($pdo),
        migrateSchema($pdo),
        addForeignKeys($pdo),
        ensureAdmin($pdo, $adminEmail)
    );
} catch (Throwable $error) {
    $installationSucceeded = false;
    $messages[] = 'ERROR: ' . $error->getMessage();
}

if ($installationSucceeded) {
    header('Location: login.php');
    exit;
}

function escapeHtml(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Installation Failed | IMS Nepal</title>
</head>
<body>
    <div class="box">
        <h1>Installation Failed</h1>

        <ul>
            <?php foreach ($messages as $message): ?>
                <li><?= escapeHtml($message) ?></li>
            <?php endforeach; ?>
        </ul>

        <a class="retry" href="install.php">Retry Installation</a>

        <p class="hint">
            1. Start MySQL on port <?= $port; ?><br>
            2. Click Retry Installation.<br>
            Admin login after installation:
            <strong>admin</strong> / Password123$
        </p>
    </div>
</body>
</html>
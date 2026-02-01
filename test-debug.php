<?php
// Тестовый файл для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Info</h1>";
echo "<pre>";

// 1. Проверяем REQUEST_URI
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
echo "__DIR__: " . __DIR__ . "\n\n";

// 2. Проверяем файлы
$files = [
    'Parsedown.php' => __DIR__ . '/lib/Parsedown.php',
    'guides-config.php' => __DIR__ . '/guides-config.php',
    'soyuz-dangers.md' => __DIR__ . '/content/guides/soyuz-dangers.md',
    'vvedenie.md' => __DIR__ . '/content/guides/vvedenie.md',
];

echo "=== FILES CHECK ===\n";
foreach ($files as $name => $path) {
    $exists = file_exists($path) ? 'EXISTS' : 'NOT FOUND';
    echo "$name: $exists ($path)\n";
}

// 3. Пробуем подключить guides-config
echo "\n=== LOADING guides-config.php ===\n";
try {
    require_once __DIR__ . '/guides-config.php';
    echo "guides-config.php loaded OK\n";
    echo "guideExists('vvedenie'): " . (guideExists('vvedenie') ? 'YES' : 'NO') . "\n";
    echo "guideExists('soyuz-dangers'): " . (guideExists('soyuz-dangers') ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// 4. Пробуем Parsedown
echo "\n=== LOADING Parsedown ===\n";
try {
    require_once __DIR__ . '/lib/Parsedown.php';
    $parsedown = new Parsedown();
    $html = $parsedown->text("# Test\n\nHello **world**!");
    echo "Parsedown OK: " . strlen($html) . " bytes\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// 5. Пробуем прочитать MD файл
echo "\n=== READING MD FILE ===\n";
$mdPath = __DIR__ . '/content/guides/vvedenie.md';
if (file_exists($mdPath)) {
    $content = file_get_contents($mdPath);
    echo "vvedenie.md: " . strlen($content) . " bytes\n";
    echo "First 100 chars: " . substr($content, 0, 100) . "\n";
} else {
    echo "vvedenie.md NOT FOUND\n";
}

echo "</pre>";
echo "<p><a href='/'>← Back to home</a></p>";

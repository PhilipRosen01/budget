<?php
// Simple PHP test file for Hostinger debugging
// Place this in your public/ folder and visit yourdomain.com/hostinger-test.php

echo "<h1>Hostinger Laravel Debug Test</h1>";
echo "<hr>";

echo "<h2>1. PHP Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

echo "<h2>2. File System Check</h2>";
$files_to_check = [
    '../.env',
    '../vendor/autoload.php',
    '../storage',
    '../bootstrap',
    'index.php',
    '.htaccess'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
        if (is_dir($file)) {
            echo "&nbsp;&nbsp;&nbsp;└── Directory permissions: " . substr(sprintf('%o', fileperms($file)), -4) . "<br>";
        }
    } else {
        echo "❌ $file missing<br>";
    }
}

echo "<h2>3. Environment Test</h2>";
if (file_exists('../.env')) {
    echo "✅ .env file found<br>";
    $env_content = file_get_contents('../.env');
    echo "APP_ENV: " . (preg_match('/APP_ENV=(.*)/', $env_content, $matches) ? $matches[1] : 'Not found') . "<br>";
    echo "APP_DEBUG: " . (preg_match('/APP_DEBUG=(.*)/', $env_content, $matches) ? $matches[1] : 'Not found') . "<br>";
    echo "DB_CONNECTION: " . (preg_match('/DB_CONNECTION=(.*)/', $env_content, $matches) ? $matches[1] : 'Not found') . "<br>";
} else {
    echo "❌ .env file not found<br>";
}

echo "<h2>4. Laravel Bootstrap Test</h2>";
try {
    if (file_exists('../bootstrap/app.php')) {
        echo "✅ Laravel bootstrap file exists<br>";
        // Don't actually bootstrap Laravel here as it might cause issues
        echo "✅ Ready to test Laravel bootstrap<br>";
    } else {
        echo "❌ Laravel bootstrap file missing<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<h2>5. Directory Structure</h2>";
echo "<pre>";
function listDirectory($dir, $prefix = '') {
    if (!is_dir($dir)) return;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        echo $prefix . $item;
        if (is_dir($dir . '/' . $item)) {
            echo "/\n";
            if (strlen($prefix) < 6) { // Limit depth
                listDirectory($dir . '/' . $item, $prefix . '  ');
            }
        } else {
            echo "\n";
        }
    }
}

echo "Current directory structure:\n";
listDirectory('.');
echo "</pre>";

echo "<h2>6. Next Steps</h2>";
echo "<p>If you see this page, PHP is working. Check the results above:</p>";
echo "<ul>";
echo "<li>If .env is missing, copy .env.production to .env</li>";
echo "<li>If vendor/autoload.php is missing, run: composer install</li>";
echo "<li>If storage directory has wrong permissions, run: chmod -R 755 storage</li>";
echo "<li>If everything looks good, the issue might be in Laravel's bootstrap process</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Generated at:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>
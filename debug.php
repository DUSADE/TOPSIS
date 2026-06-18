<?php
// Taruh file ini di public/debug.php SEMENTARA untuk cek error
// HAPUS setelah selesai debug!

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre>";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Working dir: " . getcwd() . "\n";
echo "APP_KEY set: " . (getenv('APP_KEY') ? 'YES (' . substr(getenv('APP_KEY'), 0, 10) . '...)' : 'NO - INI MASALAHNYA!') . "\n";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";

$dbPath = getenv('DB_DATABASE') ?: '/app/database/database.sqlite';
echo "DB file exists: " . (file_exists($dbPath) ? 'YES' : 'NO - FILE TIDAK ADA!') . "\n";
echo "DB readable: " . (is_readable($dbPath) ? 'YES' : 'NO') . "\n";
echo "DB writable: " . (is_writable($dbPath) ? 'YES' : 'NO') . "\n";

echo "\nstorage/framework writable: " . (is_writable('../storage/framework') ? 'YES' : 'NO - INI MASALAHNYA!') . "\n";
echo "bootstrap/cache writable: " . (is_writable('../bootstrap/cache') ? 'YES' : 'NO - INI MASALAHNYA!') . "\n";

echo "\nvendor/autoload exists: " . (file_exists('../vendor/autoload.php') ? 'YES' : 'NO - INI MASALAHNYA!') . "\n";

echo "\n--- ENV VARS ---\n";
$keys = ['APP_KEY','APP_ENV','APP_DEBUG','APP_URL','DB_CONNECTION','DB_DATABASE','SESSION_DRIVER','CACHE_DRIVER'];
foreach ($keys as $k) {
    echo "$k = " . (getenv($k) ?: '(kosong)') . "\n";
}
echo "</pre>";

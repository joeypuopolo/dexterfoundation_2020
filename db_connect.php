<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_USER', 'giuseppepuopolo');
define('DB_PASSWORD', 'dairymilkisbad');

$dsn = 'mysql:host=mysql.dexterfoundation.com;dbname=dexterfoundation';

try {
    $db = new PDO($dsn, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $err_msg = 'Database connection error.'; // Keep it generic for safety
    include('./db_error.php');
    exit();
}
?>

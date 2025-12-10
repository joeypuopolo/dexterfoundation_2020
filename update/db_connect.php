<?php
DEFINE('DB_USER', 'giuseppepuopolo');
DEFINE('DB_PASSWORD', 'dairymilkisbad');

$dsn = 'mysql:host=mysql.dexterfoundation.com;dbname=dexterfoundation';

try {
	$db = new PDO($dsn , DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
	$err_msg = $e->getMessage();
	include('./db_error.php');
	exit();
}
?>
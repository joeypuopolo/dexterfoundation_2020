<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$debug = true;

$db_hostname = "mysql.dexterfoundation.com";
$db_database = "dexterfoundation";
$db_username = "giuseppepuopolo";
$db_password = "dairymilkisbad";

$base_url = "http://www.dexterfoundation.com/update/";

$doggie_img_url = "/img/doggies/";
$doggie_success_url = "/img/success/";
$applications_url = "/leases/";

$default_email = "contactus@dexterfoundation.com";

$admin_username = "admin";
$admin_password = "497dexter";


?>
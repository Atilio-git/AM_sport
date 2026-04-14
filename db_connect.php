<?php
$host ='localhost';
$dbname ='AM_SPORT';
$DB_USER = 'root';
$DB_PASS = 'akatsuki';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $DB_USER, $DB_PASS);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

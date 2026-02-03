<?php
$host = 'localhost';
$dbname = 'sistem_undangan_wedding';
$username = 'root';
$password = '';

// PRODUCTION SETTINGS (Prevent stuck UI)
error_reporting(0);
ini_set('display_errors', 0); 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

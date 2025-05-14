<?php
// config.php - Database Configuration File

$db_host = 'localhost';
$db_name = 'ProGearHub'; // Database name
$db_user = 'root';       // Default XAMPP username
$db_pass = '';           // Default XAMPP password (empty)
$db_charset = 'utf8mb4';

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Global $pdo variable for database connection
// This will be used by process_order.php and process_enquiry.php
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    // For the user, show a generic error. The actual error is logged.
    die("Sorry, there was a problem connecting to the database. Please try again later or contact support.");
}
?>

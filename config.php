<?php
// Database Configuration File - config.php

// Database connection settings
// Change as needed for XAMPP
// (usually ‘root’ as user and blank password are the XAMPP defaults)
define("DB_HOST", "localhost");
define("DB_NAME", "ProGearHub"); 
define("DB_USER", "root");
define("DB_PASS", ""); // Blank password by default in XAMPP
define("DB_CHARSET", "utf8mb4");

// Data Source Name (DSN) for the connection PDO
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// Options for the PDO connection
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws exceptions in case of error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Returns the results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disables emulation of prepared statements for security purposes
];

// Global variable for the PDO connection
$pdo = null;

try {
    // Try establishing the PDO connection
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // If the connection fails, it displays a generic error message and logs the actual error.
  
    error_log("Database Connection Error: " . $e->getMessage());
    die("Sorry, there was a problem connecting to the database. Please try again later or contact support.");
}


// require_once 'config.php';
// $stmt = $pdo->prepare("SELECT * FROM sua_tabela");
// ...
?>

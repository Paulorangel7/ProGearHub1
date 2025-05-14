<?php
// process_order.php - Processes the order form and inserts into the database

require_once 'config.php'; // Uses the $pdo connection from config.php

$response_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data - names MUST match the HTML form field names
    $customer_name = isset($_POST['customer_name']) ? htmlspecialchars(trim($_POST['customer_name'])) : '';
    $customer_email = isset($_POST['customer_email']) ? filter_var(trim($_POST['customer_email']), FILTER_SANITIZE_EMAIL) : '';
    $product_id = isset($_POST['product_id']) ? htmlspecialchars(trim($_POST['product_id'])) : ''; // product_id from the select field
    $quantity = isset($_POST['quantity']) ? filter_var(trim($_POST['quantity']), FILTER_VALIDATE_INT) : '';
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';

    // Basic validation
    if (empty($customer_name) || empty($customer_email) || empty($product_id) || $quantity === '' || empty($address)) {
        $error_message = "Please fill in all fields for the order: Full Name, Email, Product, Quantity, and Address.";
    } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($quantity === false || $quantity <= 0) { // Check if filter_var returned false or quantity is not positive
        $error_message = "Quantity must be a positive integer.";
    } else {
        try {
            // $pdo is already available from config.php

            // Prepare SQL query for insertion into the 'orders' table
            // Columns in 'orders' table: customer_name, customer_email, product_id, quantity, address
            $sql = "INSERT INTO orders (customer_name, customer_email, product_id, quantity, address) VALUES (:customer_name, :customer_email, :product_id, :quantity, :address)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':customer_name', $customer_name);
            $stmt->bindParam(':customer_email', $customer_email);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':address', $address);

            // Execute the query
            if ($stmt->execute()) {
                $response_message = "Order placed successfully! Thank you, " . htmlspecialchars($customer_name) . ".";
            } else {
                $error_message = "Error placing order. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Database error in process_order.php: " . $e->getMessage()); 
            $error_message = "A critical error occurred while processing your order. Please contact support.";
        }
    }
} else {
    $error_message = "Invalid request method. This script must be accessed via POST.";
}

// Display response or error message
if (!empty($response_message)) {
    echo "<p style='color: green;'>" . $response_message . "</p>";
    echo "<p><a href='order.html'>Back to Order Form</a></p>"; // Link back to order.html
}
if (!empty($error_message)) {
    echo "<p style='color: red;'>" . $error_message . "</p>";
    echo "<p><a href='order.html'>Back to Order Form</a></p>"; // Link back to order.html
}
?>

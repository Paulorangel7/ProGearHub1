<?php


require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Field names according to the order.html form 
    $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Basic validation of mandatory fields
    if (empty($customer_name) || empty($email) || empty($product_name) || !isset($quantity) || !isset($price)) {
        die('Error: Customer name, email, product name, quantity and price are mandatory.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Error: Invalid e-mail format.');
    }

    if (!filter_var($quantity, FILTER_VALIDATE_INT) || $quantity <= 0) {
        die('Error: The quantity must be a positive integer.');
    }

    if (!filter_var($price, FILTER_VALIDATE_FLOAT) || $price <= 0) {
        die('Error: The price must be a positive number.');
    }

    try {
        // SQL query to insert the data into the ‘orders’ table 
        // total_price is generated automatically by the bank
        $sql = "INSERT INTO orders (customer_name, email, product_name, quantity, price) 
                VALUES (:customer_name, :email, :product_name, :quantity, :price)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':customer_name', $customer_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR); // PDO::PARAM_STR is safer for decimals, PDO handles the conversion.
        
        if ($stmt->execute()) {
            echo "Order successful! Thank you, " . htmlspecialchars($customer_name) . ".";
        } else {
            echo "Sorry, there was an error processing your order. Please try again.";
            error_log("Error performing insertion in orders table .");
        }

    } catch (PDOException $e) {
        error_log("Database error when processing order : " . $e->getMessage());
        echo "A critical error occurred while processing your order. Please contact support.";
    }

} else {
    echo "Invalid request method. This script must be accessed via POST.";
    exit;
}
?>

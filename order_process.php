<?php
// Include the database configuration file that already uses PDO
require_once ‘config.php’; // 
// Checks if the form has been sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, ‘address’, FILTER_SANITIZE_STRING); // Assuming that ‘address’ comes from the order.html form
    $product = filter_input(INPUT_POST, ‘product’, FILTER_SANITIZE_STRING); // Assuming that ‘product’ comes from the order.html form
    $quantity = filter_input(INPUT_POST, ‘quantity’, FILTER_SANITIZE_NUMBER_INT); // Assuming that ‘quantity’ comes from the order.html form

// Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'ProGearHub');

  // Check the connection
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo ‘Error: All fields are required.’;
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo ‘Error: Invalid e-mail format.’;
        exit;
    }

    try {
        // Prepare the SQL statement to insert the message
        // ATTENTION: The original script inserts into the ‘contact_us’ table.
        // The XAMPP image shows an ‘evaluations’ table.
       
        // This example continues to use ‘contact_us’ as per the original script.
        $sql = ‘INSERT INTO contact_us (name, email, subject, message) VALUES (:name, :email, :subject, :message)’;
        $stmt = $pdo->prepare($sql);

        // Associates the query parameters with the variables
 $stmt = $pdo->prepare($sql);
        $comment_from_form = "Pedido de: " . $name . "\n";
        $comment_from_form .= "Email: " . $email . "\n";
        $comment_from_form .= "Endereço: " . $address . "\n";
        $comment_from_form .= "Produto: " . $product . "\n";
        $comment_from_form .= "Quantidade: " . $quantity;

 $stmt->bindParam(':comment', $comment_from_form, PDO::PARAM_STR);
        
 // Execute the query
        if ($stmt->execute()) {
            echo ‘Message sent successfully!’;
            // You can redirect the user to a thank you page here
            // header(‘Location: thanks.html’);
            // exit;
        } else {
            echo ‘Error sending message. Try again.’;
        }
    } catch (PDOException $e) {
        // In a production environment, don't display detailed error messages to the user
        // Log the error to a file or monitoring system
        error_log("Database error: ’ . $e->getMessage());
        echo ‘There was an error processing your request. Please try again later.’;
        // exit;
    }
    // The PDO connection is usually closed automatically when the script ends
    // or when the $pdo object is destroyed.
} else {
    // If the method is not a POST, redirect or display an error message
    echo ‘Invalid request method.’;
    // header(‘Location: contact.html’);
    // exit;
?>

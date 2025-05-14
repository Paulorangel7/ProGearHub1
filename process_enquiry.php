<?php
// process_enquiry.php - Processes the contact form and inserts into the database

require_once 'config.php'; // Uses the $pdo connection from config.php

$response_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data - names MUST match the HTML form field names
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message_text = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $error_message = "Please fill in all fields for the enquiry.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        try {
            // $pdo is already available from config.php

            // Prepare SQL query for insertion into the 'enquiries' table
            // Columns in 'enquiries' table: name, email, subject, message
            $sql = "INSERT INTO enquiries (name, email, subject, message) VALUES (:name, :email, :subject, :message_text)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message_text', $message_text);

            // Execute the query
            if ($stmt->execute()) {
                $response_message = "Enquiry sent successfully! Thank you, " . htmlspecialchars($name) . ". We will get back to you soon.";
            } else {
                $error_message = "Error sending enquiry. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Database error in process_enquiry.php: " . $e->getMessage()); 
            $error_message = "A critical error occurred while processing your enquiry. Please contact support.";
        } 
        // No need to explicitly close $pdo here if it's managed globally or at script end by PHP
    }
} else {
    $error_message = "Invalid request method. This script must be accessed via POST.";
}

// Display response or error message
if (!empty($response_message)) {
    echo "<p style='color: green;'>" . $response_message . "</p>";
    echo "<p><a href='contact.html'>Back to Contact Form</a></p>"; // Link back to contact.html
}
if (!empty($error_message)) {
    echo "<p style='color: red;'>" . $error_message . "</p>";
    echo "<p><a href='contact.html'>Back to Contact Form</a></p>"; // Link back to contact.html
}
?>

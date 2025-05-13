<?php
// process_enquiry_english.php - Processes the contact form and inserts into the database 

require_once 'config.php'; // 

$response_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data (ensure names match HTML form fields)
    $visitor_name = isset($_POST['visitor_name']) ? htmlspecialchars(trim($_POST['visitor_name'])) : '';
    $visitor_email = isset($_POST['visitor_email']) ? filter_var(trim($_POST['visitor_email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message_text = isset($_POST['message_text']) ? htmlspecialchars(trim($_POST['message_text'])) : ''; // Assuming 'message_text' will be the name in HTML

    // Basic validation
    if (empty($visitor_name) || empty($visitor_email) || empty($subject) || empty($message_text)) {
        $error_message = "Please fill in all fields for the enquiry.";
    } elseif (!filter_var($visitor_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        try {
            // Connect to the database using PDO from config.php
            $pdo = new PDO($dsn, $db_user, $db_pass, $options);

            // Prepare SQL query for insertion into the 'enquiries' table
            // Columns: enquiry_id (AUTO_INCREMENT), visitor_name, visitor_email, subject, message, submission_date (DEFAULT CURRENT_TIMESTAMP)
            $sql = "INSERT INTO enquiries (visitor_name, visitor_email, subject, message) VALUES (:visitor_name, :visitor_email, :subject, :message_text)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':visitor_name', $visitor_name);
            $stmt->bindParam(':visitor_email', $visitor_email);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message_text', $message_text);

            // Execute the query
            if ($stmt->execute()) {
                $response_message = "Enquiry sent successfully! Thank you, " . htmlspecialchars($visitor_name) . ". We will get back to you soon.";
            } else {
                $error_message = "Error sending enquiry. Please try again.";
            }
        } catch (PDOException $e) {
            // error_log("Database error: " . $e->getMessage()); // Log actual error
            $error_message = "A critical error occurred while processing your enquiry. Please contact support.";
        } finally {
            $pdo = null; // Close connection
        }
    }
} else {
    $error_message = "Invalid request method. This script must be accessed via POST.";
}

// Display response or error message
if (!empty($response_message)) {
    echo "<p style='color: green;'>" . $response_message . "</p>";
    echo "<p><a href='contact_english.html'>Back to Contact Form</a></p>";
}
if (!empty($error_message)) {
    echo "<p style='color: red;'>" . $error_message . "</p>";
    echo "<p><a href='contact_english.html'>Back to Contact Form</a></p>";
}
?>

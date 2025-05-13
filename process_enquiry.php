<?php
// process_enquiry.php - Handles the sending of the contact form

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Field names according to the contact.html form and the contact_us table
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message_content = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING); 

    if (empty($name) || empty($email) || empty($subject) || empty($message_content)) {
        die('Error: Name, email, subject and message are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Error: Invalid e-mail format.');
    }

    try {
        // SQL query to insert data into the ‘contact_us’ table
        $sql = "INSERT INTO contact_us (name, email, subject, message) 
                VALUES (:name, :email, :subject, :message_content)";
        
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':message_content', $message_content, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Message sent successfully! Thank you, " . htmlspecialchars($name) . ". We'll be in touch soon.";
        } else {
            echo "Sorry, there was an error sending your message. Please try again.";
            error_log("Error performing insertion in the contact_us table.’);
        }

    } catch (PDOException $e) {
        error_log("Database error when processing contact message: " . $e->getMessage());
        echo "A critical error occurred while processing your message. Please contact support.";
    }

} else {
    echo "Invalid request method. This script must be accessed via POST.";
    exit;
}
?>

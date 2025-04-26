<?php
// Checks if the form has been sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

// Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'ProGearHub');

  // Check the connection
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Prepare SQL to insert the message
    $sql = "INSERT INTO contact_us (name, email, subject, message)
            VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Mensagem enviada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

   // Close the connection
    $conn->close();
}
?>

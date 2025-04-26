<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário
    $name = $_POST['customer_name'];
    $email = $_POST['email'];
    $product = $_POST['product_name'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    // Calcula o preço total
    $total_price = $quantity * $price;

    // Conecta ao banco de dados
    $conn = new mysqli('localhost', 'root', '', 'ProGearHub');

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Prepara o SQL para inserir o pedido
    $sql = "INSERT INTO orders (customer_name, email, product_name, quantity, price)
            VALUES ('$name', '$email', '$product', '$quantity', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "Pedido enviado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
}
?>

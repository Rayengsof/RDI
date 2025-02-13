<?php
session_start(); // Inicia a sessão para pegar o usuário logado

$servername = "db_rdi.mysql.dbaas.com.br"; 
$username = "db_rdi"; 
$password = "Rafinha@250591"; 
$dbname = "db_rdi"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_POST['message'])) {
    $message = $_POST['message'];

    // Pegando o nome do usuário logado e fazendo a primeira letra maiúscula
    if (isset($_SESSION['username'])) {
        $user_name = ucfirst(strtolower($_SESSION['username'])); // Primeira maiúscula, o restante minúsculo
    } else {
        $user_name = 'DESCONHECIDO'; // Caso não haja usuário logado, atribui 'DESCONHECIDO'
    }

    // Inserindo a mensagem no banco de dados com is_read = 1 (não lida)
    $stmt = $conn->prepare("INSERT INTO messages (user_name, message, is_read, is_verified) VALUES (?, ?, 1, 0)");  
    $stmt->bind_param("ss", $user_name, $message);
    $stmt->execute();
    $stmt->close();
}
  
$conn->close();
?>

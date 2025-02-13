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

$user_name = $_SESSION['username'];

// Recuperar todas as mensagens
$query = "SELECT id, user_name, message, is_read, is_verified FROM messages ORDER BY id DESC";  
$result = mysqli_query($conn, $query);

// Preparar o resultado em formato JSON para o JavaScript
$messages = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Se a mensagem for não lida e foi enviada por outra pessoa, marcar como lida
    if ($row['is_read'] == 1 && $row['user_name'] != $user_name) {
        $updateQuery = "UPDATE messages SET is_read = 0 WHERE id = {$row['id']} AND user_name != '{$user_name}'";
        mysqli_query($conn, $updateQuery);
    }
    $messages[] = $row;
}

// Retornar as mensagens para o front-end
echo json_encode($messages);

mysqli_close($conn);
?>

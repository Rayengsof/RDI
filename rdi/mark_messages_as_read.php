<?php
// Conexão com o banco de dados
$servername = "db_rdi.mysql.dbaas.com.br";
$username = "db_rdi";
$password = "Rafinha@250591";
$dbname = "db_rdi";

$conn = new mysqli($servername, $username, $password, $dbname);
<?php
// mark_messages_as_read.php

// Conexão com o banco de dados
include 'db_connection.php';
session_start();

// Pegando o nome do usuário que está visualizando as mensagens
$user_name = $_SESSION['username'];

// Atualizar o status das mensagens para "lidas"
$sql = "UPDATE messages SET is_read = 0 WHERE is_read = 1 AND user_name != '{$user_name}'";
if ($conn->query($sql) === TRUE) {
    echo "Mensagens marcadas como lidas.";
} else {
    echo "Erro ao marcar mensagens: " . $conn->error;
}

$conn->close();
?>

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Pegando o nome do usuário logado
session_start();
$user_name = $_SESSION['username'];

// Atualizar o status das mensagens para "lidas", mas apenas para as mensagens que não foram enviadas pelo próprio usuário
$sql = "UPDATE messages SET is_read = 0 WHERE is_read = 1 AND user_name != '{$user_name}'";
if ($conn->query($sql) === TRUE) {
    echo "Mensagens marcadas como lidas.";
} else {
    echo "Erro ao marcar mensagens: " . $conn->error;
}

$conn->close();
?>
 
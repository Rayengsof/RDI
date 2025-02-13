<?php
$servername = "db_rdi.mysql.dbaas.com.br"; 
$username = "db_rdi"; 
$password = "Rafinha@250591"; 
$dbname = "db_rdi"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_POST['user_name'])) {
    $user_name = $_POST['user_name'];

    $sql = "INSERT INTO users (user_name) VALUES ('$user_name')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Usuário registrado com sucesso!";
    } else {
        echo "Erro ao registrar usuário: " . $conn->error;
    }
}

$conn->close();
?>

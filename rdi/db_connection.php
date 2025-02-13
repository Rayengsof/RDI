<?php
// Informações de conexão com o banco de dados
$servername = "db_rdi.mysql.dbaas.com.br"; // Servidor de banco de dados
$username = "db_rdi";                      // Nome de usuário
$password = "Rafinha@250591";              // Substitua "sua_senha" pela senha correta
$dbname = "db_rdi";                        // Nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
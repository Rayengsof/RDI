<?php
// Conexão com o banco de dados
$servername = "db_rdi.mysql.dbaas.com.br"; 
$username = "db_rdi"; 
$password = "Rafinha@250591"; 
$dbname = "db_rdi"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_user_name = $_POST['username'];
    $new_user_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar se as senhas coincidem
    if ($new_user_password != $confirm_password) {
        echo "As senhas não coincidem!";
        exit();
    }

    // Verificar se o nome de usuário já existe
    $sql = "SELECT id FROM users WHERE username = '$new_user_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Nome de usuário já existe!";
        exit();
    }

    // Criar o hash da senha
    $hashed_password = password_hash($new_user_password, PASSWORD_DEFAULT);

    // Inserir no banco de dados
    $sql = "INSERT INTO users (username, password) VALUES ('$new_user_name', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo usuário cadastrado com sucesso!";
        header("Location: login.php");  // Redireciona para a página de login após o cadastro
        exit();
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }
}

$conn->close();
?>

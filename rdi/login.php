<?php
session_start(); // Inicia a sessão

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

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];

    // Consultar o banco de dados para pegar o hash da senha
    $sql = "SELECT id, username, password FROM users WHERE username = '$user_name'";
    $result = $conn->query($sql);

    // Verificar se o usuário existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verificar a senha fornecida com o hash
        if (password_verify($user_password, $hashed_password)) {
            // Senha correta, logado com sucesso
            $_SESSION['user_id'] = $row['id'];  // Armazenando o ID do usuário na sessão
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");  // Redireciona para a página principal
            exit();
        } else {
            // Senha incorreta
            header("Location: login.php?error=1");  // Redireciona de volta com erro  
            exit();
        }
    } else {
        // Usuário não encontrado
        header("Location: login.php?error=1");  // Redireciona com erro
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RDI</title>
    <link rel="stylesheet" href="CSS/styles.css">
   <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    .login-container h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .login-container input {
        width: 100%;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    .login-container button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    .login-container button:hover {
        background-color: #0056b3;
    }

    .login-container .register-btn {
        margin-top: 10px;
        font-size: 14px;
        color: #007bff;
        background: none;
        border: none;
        cursor: pointer;
    }

    .login-container .register-btn:hover {
        text-decoration: underline;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 50px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Mensagens de erro */
    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 10px;
    }

    /* Media Queries para responsividade */
    @media (max-width: 768px) {
        .login-container {
            padding: 20px;
            max-width: 90%;
        }

        .login-container h2 {
            font-size: 20px;
        }

        .login-container input,
        .login-container button {
            font-size: 15px;
            padding: 10px;
        }

        .login-container .register-btn {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 15px;
            max-width: 90%;
        }

        .login-container h2 {
            font-size: 18px;
        }

        .login-container input,
        .login-container button {
            font-size: 14px;
            padding: 8px;
        }

        .login-container .register-btn {
            font-size: 12px;
        }

        .modal-content {
            width: 90%;
        }
    }
</style>

</head>
<body>

<!-- Container de Login -->
<div class="login-container">
    <h2>Login RDI</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Usuário" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

    <button class="register-btn" id="btnCadastrar">Cadastrar</button>

    <?php
    // Exibir erros de login
    if (isset($_GET['error'])) {
        echo "<p class='error-message'>Usuário ou senha incorretos!</p>";
    }
    ?>
</div>

<!-- Modal de Cadastro -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Cadastrar Novo Usuário</h2>
        <form method="POST" action="cadastrar.php">
            <input type="text" name="username" placeholder="Nome de usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <input type="password" name="confirm_password" placeholder="Confirmar Senha" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</div>

<script>
    // Abrir e Fechar Modal de Cadastro
    var modal = document.getElementById("myModal");
    var btnCadastrar = document.getElementById("btnCadastrar");
    var closeModal = document.getElementById("closeModal");

    // Abrir modal ao clicar no botão Cadastrar
    btnCadastrar.onclick = function() {
        modal.style.display = "block";
    }

    // Fechar modal ao clicar no "X"
    closeModal.onclick = function() {
        modal.style.display = "none";
    }

    // Fechar modal se o usuário clicar fora da caixa
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>


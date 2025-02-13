<?php
// Conectar ao banco de dados

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

if (isset($_POST['message_id']) && isset($_POST['user_name'])) {
    $message_id = $_POST['message_id'];
    $user_name = $_POST['user_name'];

    // Recuperar o campo 'visto' atual da mensagem
    $query = "SELECT visto FROM messages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->bind_result($visto);
    $stmt->fetch();

    // Se a coluna 'visto' for nula ou vazia, inicializamos como um array vazio
    $visto = $visto ? json_decode($visto, true) : [];

    // Adicionar o nome do usuário ao campo 'visto' se não estiver lá
    if (!in_array($user_name, $visto)) {
        $visto[] = $user_name;
    }

    // Atualizar a mensagem no banco de dados com o novo campo 'visto'
    $query = "UPDATE messages SET visto = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $visto_json = json_encode($visto);
    $stmt->bind_param("si", $visto_json, $message_id);
    $stmt->execute();

    echo 'Mensagem atualizada como vista por ' . $user_name;
} else {
    echo 'Erro: ID da mensagem ou nome do usuário não fornecido.';
}  

$conn->close();
?>

<?php
session_start(); // Inicia a sessão para verificar se o usuário está logado
// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit(); // Interrompe a execução do script para evitar o carregamento da página
}
// Informações de conexão com o banco de dados
$servername = "db_rdi.mysql.dbaas.com.br";
$username = "db_rdi";
$password = "Rafinha@250591";
$dbname = "db_rdi";
$conn = new mysqli($servername, $username, $password, $dbname);
// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
// Adicionar, editar ou excluir dados
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $descricao_produto = $_POST['descricao_produto'];
        $prefixo = $_POST['prefixo'];
        $cod_rm = $_POST['cod_rm'];
        $fornecedor = $_POST['fornecedor'];
        $mc_pc = $_POST['mc_pc'];
        $data_entrega = $_POST['data_entrega'];
        $sql = "INSERT INTO tb_rdi (descricao_produto, prefixo, cod_rm, fornecedor, mc_pc, data_entrega) VALUES ('$descricao_produto', '$prefixo', '$cod_rm', '$fornecedor', '$mc_pc', '$data_entrega')";
        $conn->query($sql);
      
        // Redirecionar para evitar o reenvio do formulário ao atualizar a página
        header("Location: " . $_SERVER['PHP_SELF']);
        exit; // Evita que o código abaixo seja executado após o redirecionamento
       // Codigo para editar os dados existentes
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $descricao_produto = $_POST['descricao_produto'];
        $prefixo = $_POST['prefixo'];
        $cod_rm = $_POST['cod_rm'];
        $fornecedor = $_POST['fornecedor'];
        $mc_pc = $_POST['mc_pc'];
        $data_entrega = date('Y-m-d', strtotime($_POST['data_entrega']));
        $sql = "UPDATE tb_rdi 
                SET descricao_produto='$descricao_produto', prefixo='$prefixo', cod_rm='$cod_rm', 
                    fornecedor='$fornecedor', mc_pc='$mc_pc', data_entrega='$data_entrega'
                WHERE id=$id";
        $conn->query($sql);
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM tb_rdi WHERE id=$id";
        $conn->query($sql);
    }
}
// Consulta SQL para buscar dados da tabela tb_rdi
        $sql = "SELECT id, descricao_produto, prefixo , cod_rm , fornecedor , mc_pc , data_entrega FROM tb_rdi";
        $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <title>RDI Osasco</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/chat.css">
</head>
<body>
<header>
    <img src="IMG/urubu.png" alt="Imagem" id="headerImage">
    <h1 class="h1">RDI Osasco</h1>
    <div class="user-info">
        <p class="align-left">Bem-vindo, <?php echo ucfirst(strtolower($_SESSION['username'])); ?>!</p>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Sair</button>
    </div>
</header>
<!-- Botão para adicionar novo produto -->
<div class="form-container" style="text-align:center; margin-top: 20px;">
    <button class="btn" id="btnAdd" >Adicionar itens no RDI </button>  
</div> 
 
<!-- Tabela de Produtos -->
<table>
    <thead>
        <tr>  
            <th>ID</th>
            <th>Prefixo</th>
            <th>Produto</th>
            <th>RM</th>
            <th>Fornecedor</th>
            <th>MC</th>
            <th>Previsão</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data_entrega = new DateTime($row['data_entrega']);
                $data_formatada = $data_entrega->format('d/m/Y');
                echo "<tr>
                        <td>" . $row['id'] . "</td>  
                        <td>" . $row['prefixo'] . "</td>
                        <td>" . $row['descricao_produto'] . "</td>
                        <td>" . $row['cod_rm'] . "</td>
                        <td>" . $row['fornecedor'] . "</td>
                        <td>" . $row['mc_pc'] . "</td>
                        <td>" . $data_formatada . "</td>
                        <td>
                            <button class='btn' onclick='openEditModal(" . $row['id'] . ", \"" . $row['descricao_produto'] . "\", \"" . $row['prefixo'] . "\", \"" . $row['cod_rm'] . "\", \"" . $row['fornecedor'] . "\", \"" . $row['mc_pc'] . "\", \"" . $data_formatada . "\")'>Editar</button>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' class='btn btn-danger'>Excluir</button>
                            </form>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Nenhum dado encontrado.</td></tr>";
        }
        ?>
    </tbody>
</table>
<!-- Botão de Chat -->
<div id="chatButton" class="chat-button" onclick="toggleChat()">
    <img src="IMG/chat-icon.png" alt="Chat Icon" />
</div>
<!-- Caixa de Chat -->
<div id="chatBox" class="chat-box" style="display:none;">
    <div class="chat-header">
        <span id="chatTitle">Chat</span>
        <button onclick="toggleChat()" id="closeChatBtn">X</button>
    </div>
    <div id="messages" class="chat-messages"></div>
    <input type="text" id="messageInput" placeholder="Digite sua mensagem" />  
    <button id="sendMessageButton" onclick="sendMessage()">Enviar</button>
</div>
  
  
  <!-- Modal para Adicionar/Editar Produto -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Adicionar Novo Produto</h2>
        <form method="POST">
            <input type="hidden" name="action" id="action" value="add">
            <input type="hidden" name="id" id="productId">
            <input type="text" name="descricao_produto" id="descricao_produto" placeholder="Descrição da peça" required>
            <input type="text" name="prefixo" id="prefixo" placeholder="Prefixo do carro" required>
            <input type="text" name="cod_rm" id="cod_rm" placeholder="Codigo RM" required>
            <input type="text" name="fornecedor" id="fornecedor" placeholder="Fornecedor" required>
            <input type="text" name="mc_pc" id="mc_pc" placeholder="MC/PC" required>
            <input type="date" name="data_entrega" id="data_entrega" placeholder="Previsão de entrega" required>
            <button type="submit" class="btn">Salvar</button>
        </form>
    </div>
</div>
  <!-- Script em javascript contendo logica do site  script pagina / update atualização em tempo real dos dados / chat é a logica do chat -->
<script src="JS/update.js"></script>
<script src="JS/script.js"></script>
<script src="JS/chat.js"></script>
</body>
</html>

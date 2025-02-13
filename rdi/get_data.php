<?php
$servername = "db_rdi.mysql.dbaas.com.br"; 
$username = "db_rdi"; 
$password = "Rafinha@250591"; 
$dbname = "db_rdi"; 

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para pegar os dados
$sql = "SELECT id, descricao_produto, prefixo , cod_rm , fornecedor , mc_pc , data_entrega FROM tb_rdi";
$result = $conn->query($sql);

$rows = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_entrega = new DateTime($row['data_entrega']);
        $data_formatada = $data_entrega->format('d/m/Y');

        // Cria um array de dados
        $rows[] = [
            'id' => $row['id'],
            'prefixo' => $row['prefixo'],
            'descricao_produto' => $row['descricao_produto'],
            'cod_rm' => $row['cod_rm'],
            'fornecedor' => $row['fornecedor'],
            'mc_pc' => $row['mc_pc'],
            'data_entrega' => $data_formatada
        ];
    }
}

// Retorna os dados como JSON
echo json_encode($rows);

$conn->close();
?>

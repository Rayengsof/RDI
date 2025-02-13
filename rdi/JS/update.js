// Função para atualizar a tabela
function updateTable() {
    // Faz a requisição AJAX para o arquivo get_data.php
    fetch('get_data.php') // Pega os dados da tabela
        .then(response => response.json()) // Converte a resposta para JSON
        .then(data => {
            const tableBody = document.querySelector('table tbody');
            tableBody.innerHTML = ''; // Limpa o conteúdo da tabela

            // Percorre os dados e atualiza a tabela
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.prefixo}</td>
                    <td>${row.descricao_produto}</td>
                    <td>${row.cod_rm}</td>
                    <td>${row.fornecedor}</td>
                    <td>${row.mc_pc}</td>
                    <td>${row.data_entrega}</td>
                    <td>
                        <button class='btn' onclick='openEditModal(${row.id}, "${row.descricao_produto}", "${row.prefixo}", "${row.cod_rm}", "${row.fornecedor}", "${row.mc_pc}", "${row.data_entrega}")'>Editar</button>
                        <form method='POST' style='display:inline;'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='id' value='${row.id}'>
                            <button type='submit' class='btn btn-danger'>Excluir</button>
                        </form>
                    </td>
                `;
                tableBody.appendChild(tr); // Adiciona a linha à tabela
            });
        })
        .catch(error => {
            console.error('Erro ao carregar dados:', error);
        });
}

// Atualizar a tabela a cada 5 segundos
setInterval(updateTable, 5000); // Atualiza a cada 5 segundos

// Inicializa a tabela quando a página for carregada
window.onload = updateTable;

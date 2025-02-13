// Função para abrir o modal de edição
function openEditModal(id, descricao_produto, prefixo ,cod_rm,fornecedor,mc_pc,data_formatada) {
    document.getElementById("myModal").style.display = "block";
    document.getElementById("modalTitle").innerText = "Editar Produto";
    document.getElementById("action").value = "edit";
    document.getElementById("productId").value = id;
    document.getElementById("descricao_produto").value = descricao_produto;
    document.getElementById("prefixo").value = prefixo;
    document.getElementById("cod_rm").value = cod_rm;
    document.getElementById("fornecedor").value = fornecedor;
    document.getElementById("mc_pc").value = mc_pc;

    // Convertendo a data do formato 'dd/mm/yyyy' para 'yyyy-mm-dd'
    var dataParts = data_formatada.split('/'); // Separa a data em partes: [dd, mm, yyyy]
    var data_formatada_para_input = dataParts[2] + '-' + dataParts[1] + '-' + dataParts[0]; // Forma 'yyyy-mm-dd'
    document.getElementById("data_entrega").value = data_formatada_para_input;  // Coloca no input
}

// Função para abrir o modal de adicionar produto
document.getElementById("btnAdd").onclick = function() {
    document.getElementById("myModal").style.display = "block";
    document.getElementById("modalTitle").innerText = "Adicionar RDI";
    document.getElementById("action").value = "add";
    document.getElementById("productId").value = "";
    document.getElementById("descricao_produto").value = "";
    document.getElementById("prefixo").value = "";
    document.getElementById("cod_rm").value = "";
    document.getElementById("fornecedor").value = "";
    document.getElementById("mc_pc").value = "";
    document.getElementById("data_entrega").value = "";
};

// Função para fechar o modal
function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// Fechar o modal se o usuário clicar fora da janela modal
window.onclick = function(event) {
    if (event.target == document.getElementById("myModal")) {
        closeModal();
    }
}

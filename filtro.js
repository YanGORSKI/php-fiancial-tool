document.addEventListener("DOMContentLoaded", function () {
    // Agora, seu código JavaScript será executado somente após o carregamento completo do DOM

    // Função para atualizar a tabela de acordo com os filtros selecionados
    function atualizarTabela() {
        const dataFiltro = document.getElementById("dataFiltro").value;
        const categoriaFiltro = document.getElementById("categoriaFiltro").value;
        const tabelaEntradas = document.getElementById("tabelaEntradas");

        // Realize uma solicitação AJAX para buscar os dados filtrados no backend
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            console.log(xhr.responseText);
            if (xhr.readyState === 4 && xhr.status === 200) {
                const dados = JSON.parse(xhr.responseText);
                preencherTabela(dados);
            }
        };

        // Configure a solicitação AJAX
        xhr.open("POST", "buscar_entradas.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(`dataFiltro=${dataFiltro}&categoriaFiltro=${categoriaFiltro}`);
    }

    // Função para preencher a tabela com os dados
    function preencherTabela(dados) {
        const tabelaEntradas = document.getElementById("tabelaEntradas");
        const tbody = tabelaEntradas.querySelector("tbody");
        tbody.innerHTML = ""; // Limpe o conteúdo atual da tabela

        // Preencha a tabela com os dados recebidos do servidor
        dados.forEach(function (entrada) {
            const row = tbody.insertRow();
            const dataEntradaCell = row.insertCell(0);
            const valorCell = row.insertCell(1);
            const categoriaCell = row.insertCell(2);

            console.log("LOG data Tabela" + dataEntradaCell);

            // Formate a data para exibir apenas a parte da data (sem a hora)
            const dataEntrada = new Date(entrada.data);
            const dataFormatada = formatarData(dataEntrada);

            dataEntradaCell.textContent = dataFormatada;
            valorCell.textContent = entrada.valor;
            
            // A coluna categoria já contém o nome da categoria, então não é necessário substituir
            categoriaCell.textContent = entrada.descrição;
        });
    }

    // Função para formatar a data no formato "dd/mm/yyyy"
    function formatarData(data) {
        const dia = String(data.getDate()).padStart(2, '0');
        const mes = String(data.getMonth() + 1).padStart(2, '0');
        const ano = data.getFullYear();
        return `${dia}/${mes}/${ano}`;
    }

    // Adicione um ouvinte de evento ao botão de aplicar filtro
    document.getElementById("aplicarFiltro").addEventListener("click", atualizarTabela);

    // Execute a função para preencher a tabela inicialmente
    atualizarTabela();
});
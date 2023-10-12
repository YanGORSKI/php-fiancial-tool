window.onload = function () {
    // Obtenha o botão e o campo de valor
    const tipoBotao = document.getElementById("tipoBotao");
    const valorInput = document.getElementById("valor");

    // Salve o valor atual do campo "Valor" quando o botão é alternado
    let valorSalvo = "";
    
    // Adicione um ouvinte de evento para alternar entre os estados
    tipoBotao.addEventListener("click", function () {
        if (tipoBotao.textContent === "Entrada") {
            tipoBotao.textContent = "Saída";
            tipoBotao.className = "botao-vermelho";
        } else {
            tipoBotao.textContent = "Entrada";
            tipoBotao.className = "botao-verde";
        }
        
        // Restaure o valor salvo no campo "Valor"
        valorInput.value = valorSalvo;
    });

    // Ouvinte de evento para salvar o valor quando o campo "Valor" muda
    valorInput.addEventListener("input", function () {
        valorSalvo = valorInput.value;
    });
    
    // Obtenha o botão de envio
    const submitButton = document.getElementById("submit");
    
    // Adicione um ouvinte de evento para enviar o formulário
    submitButton.addEventListener("click", function () {
        const estadoBotao = tipoBotao.textContent;
        // Adicione um campo oculto ao formulário para armazenar o estado do botão
        const estadoBotaoInput = document.createElement("input");
        estadoBotaoInput.type = "hidden";
        estadoBotaoInput.name = "estadoBotao";
        estadoBotaoInput.value = estadoBotao;
        document.querySelector("form").appendChild(estadoBotaoInput);

        // Adicione um campo oculto para a descrição da categoria
        const categoriaDescricaoInput = document.createElement("input");
        categoriaDescricaoInput.type = "hidden";
        categoriaDescricaoInput.name = "categoriaDescricao";
        categoriaDescricaoInput.value = document.getElementById("categoriaDescricao").value;
        document.querySelector("form").appendChild(categoriaDescricaoInput);
    });

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

            // Formate a data para exibir apenas a parte da data (sem a hora)
            const dataEntrada = new Date(entrada.data);
            const dataFormatada = dataEntrada.toLocaleDateString();

            dataEntradaCell.textContent = dataFormatada;
            valorCell.textContent = entrada.valor;

            // Substitua o ID da categoria pelo nome da categoria
            const categoriaId = entrada.fk_categoria_id;
            const categoriaNome = categoriaNomes[categoriaId] || "Categoria Desconhecida";
            categoriaCell.textContent = categoriaNome;
        });
    }
}
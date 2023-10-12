<?php
session_start(); // Iniciar a sessão

// date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataMovimento = $_POST["dataMovimento"];
    $valor = $_POST["valor"];
    $estadoBotao = $_POST["estadoBotao"];
    $categoriaId = $_POST["categoria"]; // ID da categoria selecionada
    $categoriaDescricao = $_POST["categoriaDescricao"]; // Descrição da categoria selecionada

    // Conexão com o banco de dados (substitua com suas configurações)
    $mysqli = new mysqli("localhost", "teste_user", "senha_teste", "php_financial_tool");

    // Verifique a conexão
    if ($mysqli->connect_error) {
        die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
    }

    // Consulta para obter a descrição da categoria selecionada
    $sqlCategoria = "SELECT descrição FROM categorias WHERE id = $categoriaId";

    // Execute a consulta
    $resultCategoria = $mysqli->query($sqlCategoria);

    // Verifique se a consulta foi bem-sucedida
    if (!$resultCategoria) {
        die("Erro na consulta de categoria: " . $mysqli->error);
    }

    // Recupere a descrição da categoria
    $rowCategoria = $resultCategoria->fetch_assoc();
    $categoriaDescricao = $rowCategoria['descrição'];

    // Substitua "," por "." para garantir um formato uniforme
    $valor = str_replace(',', '.', $valor);

    // Prepare a consulta SQL para inserir na tabela "movimentos"
    $sqlInserir = "INSERT INTO movimentos (data, valor, fk_categoria_id) VALUES (?, ?, ?)";

    // Preparar a declaração
    $stmt = $mysqli->prepare($sqlInserir);

    // Verifique se a preparação da declaração foi bem-sucedida
    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $mysqli->error);
    }

    // Determine o valor com base no estado do botão
    $valorNumerico = ($estadoBotao === "Entrada") ? $valor : -$valor;

    // Vincule os parâmetros à declaração
    $stmt->bind_param("sdi", $dataMovimento, $valorNumerico, $categoriaId);

    // Execute a declaração
    if ($stmt->execute()) {
        $_SESSION['retorno'] = "Movimento gravado: " . (($valorNumerico >= 0) ? '+' : '-') . "R$ " . number_format(abs($valorNumerico), 2, ',', '.') . " em $categoriaDescricao";
    } else {
        $_SESSION['retorno'] = "Erro ao inserir movimento: " . $stmt->error;
    }

    // Feche a declaração
    $stmt->close();

    // Feche a conexão
    $mysqli->close();

    // Redirecione de volta para index.php
    header("Location: index.php");
    exit; // Certifique-se de sair após redirecionar
} else {
    // Se a requisição não for POST, redirecione para a index.php
    header("Location: index.php");
    exit;
}
?>
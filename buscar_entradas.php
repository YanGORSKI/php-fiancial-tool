<?php
// Conexão com o banco de dados (substitua com suas configurações)
$mysqli = new mysqli("localhost", "teste_user", "senha_teste", "php_financial_tool");

// date_default_timezone_set('America/Sao_Paulo');

// Verifique a conexão
if ($mysqli->connect_error) {
die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
}

$dataFiltro = $_POST["dataFiltro"];
$categoriaFiltro = $_POST["categoriaFiltro"];

// Consulta SQL para buscar entradas com base nos filtros
$sql = "SELECT data, valor, categorias.descrição
        FROM movimentos
        INNER JOIN categorias ON movimentos.fk_categoria_id = categorias.id
        WHERE 1 ";

if (!empty($dataFiltro)) {
    $sql .= "AND data = '" . $dataFiltro . "' ";
}

if (!empty($categoriaFiltro)) {
    $sql .= "AND fk_categoria_id = " . $categoriaFiltro;
}

$result = $mysqli->query($sql);

if (!$result) {
die("Erro na consulta: " . $mysqli->error);
}

$entradas = array();

while ($row = $result->fetch_assoc()) {
$entradas[] = $row;
}

// Feche a conexão
$mysqli->close();

// Retorne os dados como JSON
echo json_encode($entradas);
?>
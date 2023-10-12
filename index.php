<?php
session_start(); // Iniciar a sessão

// Conexão com o banco de dados (substitua com suas configurações)
$mysqli = new mysqli("localhost", "teste_user", "senha_teste", "php_financial_tool");

// Verifique a conexão
if ($mysqli->connect_error) {
    die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Configure o fuso horário para o horário de Brasília (GMT-3)
// date_default_timezone_set('America/Sao_Paulo');

// Consulta para recuperar categorias
$sql = "SELECT id, descrição FROM categorias";

// Execute a consulta
$result = $mysqli->query($sql);

// Verifique se a consulta foi bem-sucedida
if (!$result) {
    die("Erro na consulta: " . $mysqli->error);
}

// Verifique se há uma mensagem de retorno na sessão e exiba-a se existir
$mensagemRetorno = "";
if (isset($_SESSION['retorno'])) {
    $mensagemRetorno = $_SESSION['retorno'];
    unset($_SESSION['retorno']); // Limpe a mensagem da sessão para não exibi-la novamente
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Controle Financeiro</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="filtro.js"></script>
</head>
<body>
<div class="container">
        <div class="top-left">
            <h1>Controle Financeiro</h1>
            <form method="post" action="processar.php">
                <label for="dataMovimento">Data:</label>
                <input type="date" id="dataMovimento" name="dataMovimento" value="<?php echo date('Y-m-d'); ?>" required>
                <br></br>
                <label for="valor">R$</label>
                <input type="text" id="valor" name="valor" placeholder="Digite o valor (ex: 100.00)" required>
                <button type="button" id="tipoBotao" class="botao-verde">Entrada</button>
                <br></br>
                <label for="categoria">Categoria:</label>
                <input type="hidden" id="categoriaDescricao" name="categoriaDescricao">
                <select id="categoria" name="categoria" required>
                    <?php
                    // Preencha as opções do dropdown com categorias
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['descrição'] . "</option>";
                    }
                    ?>
                </select>
                <br></br>
                <button type="submit" id="submit">Enviar</button>
            </form>
        </div>
        <div class="top-right">
            <?php echo '<div class="retorno">' . $mensagemRetorno . '</div>'; ?>
        </div>
        <div class="bottom-left">
            <h2>Filtrar Entradas</h2>
            <form id="filtroForm">
                <label for="dataFiltro">Data:</label>
                <input type="date" id="dataFiltro" name="dataFiltro">
                <label for="categoriaFiltro">Categoria:</label>
                <select id="categoriaFiltro" name="categoriaFiltro">
                    <option value="">Todas</option>
                    <?php
                    // Preencha as opções do dropdown com categorias
                    $result->data_seek(0); // Retorne o ponteiro do resultado para a primeira linha
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['descrição'] . "</option>";
                    }
                    ?>
                </select>
                <button type="button" id="aplicarFiltro">Aplicar Filtro</button>
            </form>
            <h2>Entradas</h2>
            <table id="tabelaEntradas">
                <thead>
                    <tr>
                        <th>Data Entrada</th>
                        <th>Valor</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- As entradas filtradas serão exibidas aqui -->
                </tbody>
            </table>
        </div>
        <div class="bottom-right">
            <p>"LUGAR RESERVADO PARA O GRÁFICO"</p>
        </div>
    </div>
</body>
</html>
<?php
// Feche a conexão
$mysqli->close();
?>
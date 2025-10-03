<?php
/**
 * Script para ver estrutura da tabela questao2
 * URL: http://www.facicinterativa.com.br/ambiente_QA/ver_estrutura_questao2.php
 */

session_start();
include './conexao.php';

if (!isset($_SESSION["usuario"])) {
    die("Acesso negado");
}

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Estrutura questao2</title>";
echo "<style>body{font-family:monospace;padding:20px;} table{border-collapse:collapse;} th,td{border:1px solid #ccc;padding:8px;text-align:left;} th{background:#333;color:white;}</style>";
echo "</head><body>";

echo "<h1>Estrutura da Tabela questao2</h1>";

// Obter estrutura da tabela
$query = "DESCRIBE questao2";
$result = mysql_query($query);

if (!$result) {
    die("Erro: " . mysql_error());
}

echo "<table>";
echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th><th>Extra</th></tr>";

$colunas = [];
while ($row = mysql_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td><strong>" . $row['Field'] . "</strong></td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
    echo "<td>" . ($row['Extra'] ? $row['Extra'] : '-') . "</td>";
    echo "</tr>";

    $colunas[] = $row['Field'];
}

echo "</table>";

echo "<h2>Total de colunas: " . count($colunas) . "</h2>";

echo "<h2>Lista de colunas:</h2>";
echo "<pre>" . implode(", ", $colunas) . "</pre>";

// Mostrar exemplo de uma questão existente
echo "<h2>Exemplo de dados reais (primeira questão):</h2>";
$queryExemplo = "SELECT * FROM questao2 LIMIT 1";
$resultExemplo = mysql_query($queryExemplo);

if ($resultExemplo && mysql_num_rows($resultExemplo) > 0) {
    echo "<table>";

    // Cabeçalho
    echo "<tr>";
    foreach ($colunas as $col) {
        echo "<th>$col</th>";
    }
    echo "</tr>";

    // Dados
    echo "<tr>";
    $row = mysql_fetch_assoc($resultExemplo);
    foreach ($colunas as $col) {
        $valor = isset($row[$col]) ? $row[$col] : 'NULL';
        if (strlen($valor) > 50) $valor = substr($valor, 0, 50) . "...";
        echo "<td>$valor</td>";
    }
    echo "</tr>";

    echo "</table>";
}

echo "<h2>Query correta para INSERT:</h2>";
echo "<pre>";
echo "INSERT INTO questao2 (" . implode(", ", $colunas) . ")\n";
echo "VALUES (?, ?, ?, ...)\n";
echo "</pre>";

echo "</body></html>";
?>

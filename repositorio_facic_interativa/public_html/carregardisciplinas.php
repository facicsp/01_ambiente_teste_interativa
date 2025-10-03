<?php
$idTurma = $_REQUEST["idTurma"];
$idAluno = 0;
$idMatricula = 1;

include './conexao.php';
$sql = "select idDisciplina, disciplina from disciplina where idTurma = '$idTurma'";

$resultados = mysql_query($sql);
$linhas = mysql_num_rows($resultados);

if($linhas > 0){
$retorno = "<label>Selecione a disciplina:</label>"
        . "<select id='txtDisciplina'><option>Escolha a disciplina:</option>";
for ($i = 0; $i < $linhas; $i++) {
    $disciplina = mysql_result($resultados, $i, "disciplina");
    $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
    $retorno .= "<option value='$idDisciplina'>$disciplina</option>";
    
}
$retorno .= "</select>";
$retorno .= "<input type=\"button\" value=\"Gravar Disciplina\" onclick=\"gravarDisciplina()\">";

echo $retorno;

    
}
?>
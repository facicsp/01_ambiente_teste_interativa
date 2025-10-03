<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="css/cadastro.css">

    <script type="text/javascript">

    function gravarNota(idAtividade, i, status) {
        var retorno = document.getElementById("txtRetorno" + i).value;
        var nota = document.getElementById("txtNota" + i).value;

        var data = new FormData();

        var arquivo = document.getElementById('txtArquivo' + i).files;
        if (arquivo.length > 0) {
            data.append('arquivo', arquivo[0])
        }

        data.append('idAtividade', idAtividade);
        data.append('nota', nota);
        data.append('retorno', retorno);
        data.append('status', status);

        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            data: data,
            url: 'gravarnota2.php',
            success: function(data) {
                if (data == "Alterado") {
                    alert("Nota atualizada com sucesso!");
                    if (status == "semdata") {
                        document.getElementById("colunaNota" + i).className = "colunanota";
                    }
                } else {
                    alert("Houve um erro ao tentar atualizar a nota/correção.");
                    document.getElementById("txtNota" + i).value = data;
                }

            }
        });
    }
    </script>

    <style>
    .colunanotaatraso {
        background-color: #ff5858;
    }

    .colunanotaatraso input[type="text"] {
        color: #FFF;
    }

    .colunanota {
        background-color: none;
    }

    .colunanota input[type="text"] {
        color: #000;
    }
    </style>

</head>

<body>
    <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador" || $_SESSION["tipo"] == "professor") {
                //conteudo do site
                include "topo.php";
                ?>



    <div class="dados" style="width:100%;margin-left: 0%;">
        <?php
                    include './conexao.php';
                    $seguranca = new Seguranca();
                    $id = "";
                    
                    if (isset($_GET["id"])) {
                        $id = $seguranca->antisql($_GET["id"]);
                        $_SESSION["idAulaNota"] = $id;
                        $_SESSION["idDisciplina"] = $seguranca->antisql($_GET["idDisciplina"]);
                    }
                    if ($_SESSION["tipo"] == "professor") {
                        $sql = "select atividade.*, date_format(data,'%d/%m/%Y') as dataAtividade, usuario.nome from atividade, usuario where atividade.idaula = '$id' and atividade.idAluno = usuario.idUsuario ORDER BY usuario.nome";
                    } else {
                        $sql = "SELECT aula.* FROM aula WHERE nome LIKE '%$consulta%' ORDER BY idAula DESC";
                    }
                    // echo $sql;
                    $resultados = mysql_query($sql);
                    $linhas = mysql_num_rows($resultados);
                    if ($linhas > 0) {
                        //$listaAlunos[];
                        echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td>Título</td>
                <td>Aluno</td>
                <td>Data de Envio</td>
                <td>Observação</td>
                <td>Atividade</td>
                <td style='width:200px;'>Correção</td>
                <td>Nota</td>
                
                
                </tr>
";
                        $idAula = mysql_result($resultados, 0, "idAula");
                        /*
                        $sqlTipo = "select desafio.tipo from desafio,aula where idaula = '$idAula' and
aula.iddesafio = desafio.iddesafio";
                        $resultTipo = mysql_query($sqlTipo);
                        //$tipoDesafio = mysql_result($resultTipo, 0, "tipo");
                        */
                        
                        for ($i = 0; $i < $linhas; $i++) {
                            $idAtividade = mysql_result($resultados, $i, "idAtividade");
                            $titulo = mysql_result($resultados, $i, "titulo");
                            $arquivo = mysql_result($resultados, $i, "arquivo");
                            $arquivo_correcao = mysql_result($resultados, $i, "arquivo_correcao");
                            $idAluno = mysql_result($resultados, $i, "idAluno");
                            $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                            $listaAlunos[$i] = $idAluno;
                            $data = mysql_result($resultados, $i, "dataAtividade");
                            $hora = mysql_result($resultados, $i, "hora");
                            $observacao = htmlentities(mysql_result($resultados, $i, "observacao"));
                            $nome = mysql_result($resultados, $i, "nome");
                            $nota = mysql_result($resultados, $i, "nota");
                            $retorno = mysql_result($resultados, $i, "retorno");
                            echo "
                      
                      <tr>
                      <td>$titulo - $idAtividade</td>
                      <td>$nome</td>
                      <td>$data $hora</td>
                      <td>$observacao</td>";
                          
$remove = "<a title='Remover arquivo de correção' href='removerArquivoCorrecao.php?id=$id&idDisciplina=$idDisciplina&idAtividade=$idAtividade&idAluno=$idAluno' style='color: red; font-weight: bold;'>X</a>";
                      
if($data == "00/00/0000" || $data == NULL || $data == "01/01/2000"){
$idAula = $_SESSION["idAulaNota"];
$idDisciplina = $_SESSION["idDisciplina"];

// if ($arquivo_correcao != "") {
//     $fileInput = "<a href='$arquivo_correcao'>Visualizar arquivo de correção</a>";
// }

echo "<td>Atividade Não Entregue</td>";
echo "<form method='post' action='alterarNota.php'>"
."<td>";

if ($arquivo_correcao == "") {
    echo "<input type='file' name='txtArquivo' id='txtArquivo$i'>";
    } else {
    echo "<a href='$arquivo_correcao' target='_blank'>Visualizar correção</a> $remove";
    }
    
    echo "<textarea style='min-width: 200px; width:100%' placeholder='Digite uma correção por escrita e/ou anexe um arquivo' name='txtRetorno' cols='150' id='txtRetorno$i'>$retorno</textarea></td>"
. "<td class='colunanotaatraso' id='colunaNota$i'><input type='text' name='txtNota' id='txtNota$i' size='10' value='$nota'>"
. "<input type='hidden' name='idAtividade' value='$idAtividade'>"
. "<input type='button' value='Gravar Nota em Atraso' onclick=\"gravarNota('$idAtividade','$i','semdata')\"></form>"
. "</td>";


}else{
echo "<td><a href='$arquivo' target='_blank'><img src='imagens/acessoArquivo.png'>Acessar Arquivo</a></td>";
echo "<form method='post' action='alterarNota.php'> 
<td>";

if ($arquivo_correcao == "") {
echo "<input type='file' name='txtArquivo' id='txtArquivo$i'>";
} else {
echo "<a href='$arquivo_correcao' target='_blank'>Visualizar correção</a> $remove";
}

echo "<textarea style='min-width: 200px; width:100%' placeholder='Digite uma correção por escrita e/ou anexe um arquivo' name='txtRetorno' id='txtRetorno$i' cols='150'>$retorno</textarea>
</td>
<td>
<input type='text' name='txtNota' id='txtNota$i' size='10' value='$nota'>
<input type='hidden' name='idAtividade' value='$idAtividade'>
<input type='button' value='Gravar Nota' onclick=\"gravarNota('$idAtividade','$i','comdata')\">
</form></td>";
}
                      
                      
                      /*
                      $mencao = "I";
                      if($tipoDesafio == "Enigma"){
                          if($nota >= 400){
                          $mencao = "MB";
                      }else if($nota >= 250){
                          $mencao = "B";
                      }else if($nota >= 100){
                          $mencao = "R";
                      }
                      }else{
                      if($nota >= 900){
                          $mencao = "MB";
                      }else if($nota >= 700){
                          $mencao = "B";
                      }else if($nota >= 450){
                          $mencao = "R";
                      }
                      }
                      echo "<td>$mencao</td>";*/
                      echo "</tr>";
                      //$mencao2[$i] = $mencao;
                        }
                    } else {
                        echo "Nenhuma registro encontrado.";
                    }
                    $sql = "select iddisciplina from aula where idaula = '$id'";
                    $result = mysql_query($sql);
                    $linhas = mysql_num_rows($result);
                    if($linhas > 0){
                        for($i=0;$i<$linhas;$i++){
                            $idDisciplina = mysql_result($result, $i, "iddisciplina");
                        }
                    }
                    $sql = "SELECT idaluno from matricula where idturma in(select idturma from aula where idaula = '$id')
                            UNION
                            SELECT idAluno AS idaluno FROM listadisciplina WHERE idDisciplina = '$idDisciplina'";
                    //echo $sql;
                    $result = mysql_query($sql);
                    $linhas = mysql_num_rows($result);
                    $quantidadeNaoEntregue = 0;
                    if($linhas > 0){
                        for($i=0;$i<$linhas;$i++){
                            $idAluno = mysql_result($result, $i, "idaluno");
                            $sqlAtividade = "select * from atividade where idaula = '$id' and idaluno = '$idAluno'";
                            $resultAtividade = mysql_query($sqlAtividade);
                            $linhasAtividade = mysql_num_rows($resultAtividade);
                            if($linhasAtividade == 0){
                                $sql = "INSERT into atividade values (null, 'Atividade', '', '$id', '$idAluno', NULL, '', '', '$idDisciplina', '0', '', '')";
                                //echo "<br>$sql";
                              //echo "<script>console.log(\"$sql\");</script>";
                                $quantidadeNaoEntregue++;
                                mysql_query($sql);
                            }
                        }
                    }
                    if($quantidadeNaoEntregue == 1){
                        echo "<script>"
                        . "alert('$quantidadeNaoEntregue atividade não foi entregue. Este aluno foi registrado com nota 0.');"
                                . "</script>";
                    }else if($quantidadeNaoEntregue > 1){
                        echo "<script>"
                        . "alert('$quantidadeNaoEntregue atividades não entregue. Estes alunos foram registrados com nota 0.');"
                                . "</script>";
                    }
                    ?>

        </table>



    </div>

    </form>
    <hr>
    <div class="dados">
        <table>
            <?php
                /*for($i=0;$i < sizeof($mencao2);$i++){
                    echo "<tr>"
                    . "<td>$mencao2[$i]</td>"
                            . "</tr>";
                }*/
                ?>
        </table>

    </div>

</body>

</html>
<?php
    } else {
        echo "Acesso negado!;";
        echo "<a href='login.html'>Faça o login!</a>";
    }
} else {
    echo "<script>"
    . "alert('É necessário fazer o login!');"
    . "window.location='login.html';"
    . "</script>";
}
?>
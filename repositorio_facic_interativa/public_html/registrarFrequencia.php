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
        <script>
            
        function corrigirFrequencia(idFrequencia,frequencia,i){
                $.ajax({
          type: 'POST',
          data: {
            'idFrequencia': idFrequencia,
            'frequencia':frequencia
        
            
          },
          url : 'corrigirFrequencia.php',
          success: function(data) {
            if(data == "Alterado"){
                alert("Frequência alterada com sucesso!");
                if(frequencia == "P"){
                document.getElementById("colunafrequencia"+i).innerHTML = "F";
                document.getElementById("colunafrequencia"+i).className = "colunafalta";
                document.getElementById("botao"+i).innerHTML = "<input type='button' value='Corrigir para Presença' onclick=\"corrigirFrequencia("+idFrequencia+",'F',"+i+")\">";
            }else if(frequencia == "F"){
                document.getElementById("colunafrequencia"+i).innerHTML = "P";
                document.getElementById("colunafrequencia"+i).className = "colunapresenca";
                document.getElementById("botao"+i).innerHTML = "<input type='button' value='Corrigir para Falta' onclick=\"corrigirFrequencia("+idFrequencia+",'P',"+i+")\">";
                }
            }else{
                alert("Houve um erro ao tentar atualizar a nota.");
                
            }
            
          }
        });
            }
            
        function marcarFalta(nome, item) {
                var conteudo = document.getElementById("mensagem" + item);
                if (document.getElementById("chamada" + item).checked) {
                    conteudo.innerHTML = "<p style='color:red;'>AUSENTE</p>";
                }else{
                    conteudo.innerHTML = "<p style='color:green;'>PRESENTE</p>";
                }
            }
        </script>
        <style>
            input[type="checkbox"]{
                width:30px;
                height:30px;
            }
            .colunafalta{
                background-color:red;
                width: 40px;
            }
            .colunapresenca{
                background-color: transparent;
            }
        </style>
    </head>
    <body>
        <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor") {
                $idAluno = $_SESSION["id"];
                include "topo.php";
                ?>


        <div class="dados">
            <div class="barratitulo"><h1>Registrar Frequência</h1></div>
                        <?php
                        include "LoginRestrito/conexao.php";
                        $seguranca = new Seguranca();
                        $id = $seguranca->antisql($_GET["id"]);
                        $idDisciplina = $_SESSION["idDisciplina"][$id];
                        $aula = $_SESSION["aula"][$id];
                        
                        $idAula = $_SESSION["idAula"][$id];
                        $data = $_SESSION["data"][$id];
                        
                        
                        
                        
                        echo "<h2>Aula: $aula</h2>";
                        $sql = "select usuario.nome,frequencia.frequencia,frequencia.idFrequencia from frequencia,usuario where idaula = '$idAula' and frequencia.idaluno = usuario.idusuario";
                        //echo $sql;
                        $result = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($result);
                        if($linhas > 0){
                            echo "<p style='color:black;'>O preenchimento da frequência nesta aula já foi realizada!</p>"
                            . "<a href='cadastroAula.php' style='color:#069;font-size:18px;'>Retornar</a>";
                            echo "<table border='1' id='consulta' align='center'>"
                            . "<tr>"
                                    . "<td style='width:25%;'>Aluno</td>"
                                    . "<td style='width:5%;'>Frequência</td>"
                                    . "<td style='width:5%;'>Corrigir Chamada</td>"
                                    . "</tr>";
                            for($i = 0;$i < $linhas;$i++){
                                $idFrequencia = mysql_result($result,$i,"idFrequencia");
                                $nome = mysql_result($result, $i, "nome");
                                $frequencia = mysql_result($result, $i, "frequencia");
                                echo "<tr>"
                                    . "<td>$nome</td>";
                                    if($frequencia == "P"){
                                    echo "<td class='colunapresenca' id='colunafrequencia$i'>$frequencia</td>";    
                                    }else{
                                    echo "<td class='colunafalta' id='colunafrequencia$i'>$frequencia</td>";
                                    }
                                    
                                    $textoFrequencia = "";
                                    if($frequencia == "P"){
                                        $textoFrequencia = "Corrigir para Falta";
                                    }else if($frequencia == "F"){
                                        $textoFrequencia = "Corrigir para Presença";
                                    }
                                    echo "<td id='botao$i'>"
                                    
                                    . "<input type='button' value='$textoFrequencia' onclick=\"corrigirFrequencia($idFrequencia,'$frequencia',$i)\">"
                                    . "</td>";
                                    echo "</tr>";
                            }
                        }else{
                        
                        
                        if ($_SESSION["tipo"] == "professor") {
                            $sql = "select usuario.nome,usuario.idUsuario from usuario,matricula where usuario.idUsuario = matricula.idAluno and matricula.idTurma in(select idTurma from disciplina WHERE idDisciplina = '$idDisciplina')
";
                        } 
                        //echo $sql;
                        $resultados = mysqli_query($conexao, $sql);
                        $linhas = mysqli_num_rows($resultados);
                        if ($linhas > 0) {
                        echo "<form method='post' action='gravarFrequencia.php'>"
                            . "<input type='hidden' name='disciplina' value='$idDisciplina'>"
                            . "<input type='hidden' name='idAula' value='$idAula'>";
                                
                            echo "<table border='1' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                <tr>
                <td style='width:40px;'>Aluno</td>
                <td style='width:40px;'>Frequência</td>
                
                
                </tr>
";
                            $registros = 0;
                            for ($i = 0; $i < $linhas; $i++) {
                                $nome = mysql_result($resultados, $i, "nome");
                                $idUsuario = mysql_result($resultados, $i, "idUsuario");
                                
                                
                                echo "
                      <tr>
                      <td style='width:25%;'>$nome</td>
                      <td style='width:5%;'><input type='checkbox' name='frequencia$i' id='chamada$i' value='F' onclick='marcarFalta(\"$nome\",\"$i\");'>"
                                        . "<div style='margin-left:10px;' id='mensagem$i'><p style='color:green;'>PRESENTE</p></div>";
                      //echo "<input type='text' disabled id='info$i' value='Presente'>";
                
                      echo "</td></tr>";
                      echo "<input type='hidden' name='idAluno$i' value='$idUsuario'>";
                      $registros++;
                        }
                        echo "<input type='hidden' name='registros' value='$registros'>"
                                . "</table>"
                                . "<div style='text-align:center;color:#069;'><p>Data: $data</p>"
                                
                                . "<input type='hidden' name='data' value='$data'>"
                                . "<input type='submit' value='Gravar'></div>"
                        . "</form>";
                        } else {
                            echo "Nenhuma registro encontrado.";
                        }
                        ?>

                
                <hr>
                <div class="voltar"><a href="index.php"><img src="imagens/voltar.png">Voltar</a></div>
                
            </div>
        </form>
        <hr>


        </body>
        </html>
        <?php
            }} else {
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
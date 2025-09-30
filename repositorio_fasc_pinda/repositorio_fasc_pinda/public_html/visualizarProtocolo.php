<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="css/cadastro.css">
</head>

<body>
  <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "administrador") {
                //conteudo do site
                include "topo.php";
                include "LoginRestrito/conexao.php";
                ?>



  <table width="950px" align="center" id="tabelaprincipal">

    <tr>
      <td>
        <center>
            <b>Consultar Protocolo</b>
            
          <form method="get" action="#" style="width: 100%;">
            
            <input type="text" name="txtProtocolo" placeholder="Número de protocolo">
            
            <input type='image' name='img_consultar' src='imagens/pesquisar.png' border='0' title='Pesquisar'>
          </form>
        </center>
        <br><br>

        <?php
                            $seguranca = new Seguranca();
                            $consulta = "";
                            if (isset($_GET["txtProtocolo"]) && $_GET["txtProtocolo"] != "") {
                                
                                $protocolo = $seguranca->antisql($_GET["txtProtocolo"]);  
                                $consulta  = "WHERE idProtocolo = '$protocolo'";
                                
                            }
                            
                            $resultados = mysqli_query($conexao, "SELECT arquivo, titulo, ra, idProtocolo, protocolo.idAtividade, exclusao FROM protocolo 
                                LEFT JOIN atividade ON protocolo.idAtividade = atividade.idAtividade 
                                LEFT JOIN usuario ON atividade.idAluno = usuario.idUsuario $consulta");
                                
                            $linhas = mysqli_num_rows($resultados);
                            
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'><tr>
                                        <td>Protocolo</td>
                                        <td>RA</td>
                                        <td>Código Atividade</td>
                                        <td>Arquivo</td>
                                      </tr>";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $idProtocolo = mysql_result($resultados, $i, "idProtocolo");
                                    $idAtividade = mysql_result($resultados, $i, "idAtividade");
                                    $exclusao = mysql_result($resultados, $i, "exclusao");
                                    $ra = mysql_result($resultados, $i, "ra");
                                    $arquivo = mysql_result($resultados, $i, "arquivo");
                                    $titulo = mysql_result($resultados, $i, "titulo");
                                    
                                    if ($exclusao != "") {
                                        $status = "<b style='color: red !important;'>Removido dia " . date_format(date_create($exclusao), "d/m/Y \à\s H:i")."</b>"; 
                                        $ra = "--";
                                    } else if ($arquivo != "") {
                                        $status = "<a href='$arquivo' target='_blank'>Visualizar</a>";
                                    } else {
                                        $status = "<b>SEM ARQUIVO ANEXADO</b>";
                                    }
                                    
                                    echo "<tr>
                                        <td>$idProtocolo</td>
                                        <td>$ra</td>
                                        <td>$idAtividade</td>
                                        <td>$status</td>
                                    </tr>";
                                    
                                }

                                 echo "</table>";
                            } else {
                                echo "Nenhuma registro encontrado.";
                            }
                ?>
      </td>
    </tr>

  </table>

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
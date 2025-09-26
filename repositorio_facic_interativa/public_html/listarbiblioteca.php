<?php
     header("Access-Control-Allow-Origin: http://ava24horas.com");
$code = $_REQUEST["code"];
if ($code == "dlskKDIJ397jkdI#EKD93jdj") {
    $tipo = $_REQUEST["tipo"];
    $retorno = "";
    if ($tipo == "p") {
        include './conexao.php';
        $sql = "select p.*,a.area from periodico p,area a 
        WHERE p.idarea = a.idarea 
        ORDER BY a.area,p.titulo";
        $result = mysql_query($sql);
        $linhas = mysql_num_rows($result);
        $areaauxiliar = "";
        if ($linhas > 0) {
            for ($i = 0; $i < $linhas; $i++) {
                $titulo = mysql_result($result, $i, "titulo");
                $link = mysql_result($result, $i, "link");
                $area = mysql_result($result, $i, "area");
                if ($area != $areaauxiliar) {
                    $retorno .= "<div style='width:100%;background-color:#069;color:#FFF;font-size:16px;float:left;'>$area</div>";
                }
                $retorno .= "<p class='periodico'>"
                        . "<a href='$link' target='_blank'>- $titulo</a>"
                        . "</p>";

                $areaauxiliar = $area;
            }
        } else {
            echo "<p>Nenhum item encontrado.</p>";
        }
    } else if ($tipo == "r") {
        include './conexao.php';
        $sql = "select * from formulario 
        ORDER BY titulo";
        $result = mysql_query($sql);
        $linhas = mysql_num_rows($result);
        $areaauxiliar = "";
 $retorno .= "<div style='width:100%;background-color:#069;color:#FFF;font-size:16px;float:left;'>Repositórios</div>";

        if ($linhas > 0) {
            for ($i = 0; $i < $linhas; $i++) {
                $titulo = mysql_result($result, $i, "titulo");
                $subtitulo = mysql_result($result, $i, "subtitulo");
                $autor = mysql_result($result, $i, "autor");
                $arquivo = mysql_result($result, $i, "arquivo");


                $retorno .= "<p class='periodico'>"
                . "<a href='../ava2/repositorio/repositorio/$arquivo' target='_blank'>- $titulo - $subtitulo (Autor: $autor)</a>"
                . "</p>";

            
            }
        } else {
            echo "<p>Nenhum item encontrado.</p>";
        }
    }
    echo $retorno;
}
?>
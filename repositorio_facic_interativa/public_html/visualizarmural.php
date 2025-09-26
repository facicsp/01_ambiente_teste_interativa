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
            if ($_SESSION["tipo"] == "professor" || $_SESSION["tipo"] == "aluno") {
                include "topo.php";
                include './conexao.php';
                $seguranca = new Seguranca();
                
                echo "<form method='get' action='visualizarmural.php'>"
                
                        . "<input type='text' name='txtConsulta' placeholder='Faça sua pesquisa aqui'>"
                        . "<input type='submit' value='Buscar'>"
                        . "</form>";
                
                
                echo "<div class='barratitulo'><h1>Novidades, Dicas e Avisos</h1></div>";
                
                $idProfessor = $_SESSION["id"];
                if(isset($_GET["id"])){
                    $idNoticia = $seguranca->antisql($_GET["id"]);
                    $sql = "SELECT n.*,d.disciplina,p.nome,date_format(n.data,'%d/%m/%Y') as data2 FROM noticia n,disciplina d,professor p WHERE n.idnoticia = '$idNoticia' AND n.iddisciplina = d.idDisciplina AND n.idprofessor = p.idProfessor";
                }else if(isset ($_GET["txtConsulta"])){
                    $consulta = $seguranca->antisql($_GET["txtConsulta"]);
                    $sql = "SELECT n.*,d.disciplina,p.nome,date_format(n.data,'%d/%m/%Y') as data2 FROM noticia n,disciplina d,professor p WHERE n.titulo LIKE '%$consulta%' AND n.iddisciplina = d.idDisciplina AND n.idprofessor = p.idProfessor AND p.idProfessor = '$idProfessor'";
                }
                else{
                    $sql = "SELECT n.*,d.disciplina,p.nome,date_format(n.data,'%d/%m/%Y') as data2 FROM noticia n,disciplina d,professor p WHERE  n.iddisciplina = d.idDisciplina AND n.idprofessor = p.idProfessor AND p.idProfessor = '$idProfessor' ORDER BY n.idnoticia DESC";
                }
                //echo $sql;
                $result = mysql_query($sql);
                $linhas = mysql_num_rows($result);
                
                if($linhas > 0){
                    for($i=0;$i<$linhas;$i++){
                    
                    $tipo = mysql_result($result, $i,"tipo");
                    $titulo = mysql_result($result, $i,"titulo");
                    $texto = mysql_result($result, $i,"texto");
                    $data = mysql_result($result, $i,"data2");
                    $disciplina = mysql_result($result, $i,"disciplina");
                    $professor = mysql_result($result, $i,"nome");
                    $status = mysql_result($result, $i,"status");
                    
                    if($tipo == "Mural"){
                        $icone = "color1 fa-newspaper-o";
                    }else if($tipo == "Aviso"){
                        $icone = "color9 fa-eye";
                    }
                    echo "<div class=\"mural\">
            
           <i class=\"icon big rounded $icone\"></i>
                    <h2>$titulo</h2>
                    <div>$texto</div>
                    <div>
                    <p style='font-size:14px;'><strong>Área: </strong>$disciplina - Postado por: $professor em $data.</p>
                    </div>
                    <hr>
                        
            </div>";
                    
                    
                    }
                }
                
                ?>


        
  <div class="voltar"><a href="index.php"><i class="icon small rounded color1 fa-arrow-left"></i> Voltar</a></div>
                
            
        </form>
        <hr>


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
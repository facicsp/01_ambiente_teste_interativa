<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/cadastro.css">
        <style>
            
        </style>
    </head>
    <body>
        
        <?php
        session_start();
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "aluno") {
                //conteudo do site
                include 'LoginRestrito/conexao.php';
                include 'topo.php';
                


                ?>
        <h1>Resultado</h1>
        <div class="resultados">
            <div style="font-weight: bold;">Pergunta</div>
            <div style="font-weight: bold;">Resposta</div>
            <div style="font-weight: bold;">Resultado</div>
        <?php
                $seguranca = new Seguranca();
                $idAluno = $_SESSION["id"];
                $idDisciplina = $_SESSION["disciplina"];
                $sql = "select q.*,r.correta,r.resposta,p.pergunta 
from questionario q,resposta r, pergunta p
where q.idDisciplina = '$idDisciplina'
and q.idAluno = '$idAluno'
and q.idResposta = r.idResposta
and q.idPergunta = p.idPergunta";
                $result = mysqli_query($conexao, $sql);
                $linhas = mysqli_num_rows($result);
                if($linhas > 0){
                    $corretas = 0;
                    $quantidade = $linhas;
                for($i=0;$i<$linhas;$i++){
                    $pergunta = mysql_result($result, $i, "pergunta");
                    $resposta = mysql_result($result, $i, "resposta");
                    $correta = mysql_result($result, $i, "correta");
                    echo "<div>$pergunta</div>";
                    echo "<div>$resposta</div>";
                    if($correta == "sim"){
                        $corretas++;
                    echo "<div>RESPOSTA CORRETA</div>";    
                    }else if($correta == "nao"){
                        echo "<div>RESPOSTA INCORRETA</div>";
                    }
                    
                    
                    
                }
                $incorretas = $quantidade - $corretas;
                $porcentagem = ($corretas*100)/$quantidade;
                $porcentagem = number_format($porcentagem, 2);
                echo "<div>Acertos: $corretas</div>";
                echo "<div>Erros: $incorretas</div>";
                echo "<div>Porcentagem de Acertos: $porcentagem%</div>";
                    
                }
                
                
                
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
</div>
    </body>
</html>
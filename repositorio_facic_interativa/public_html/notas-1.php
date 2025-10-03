<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="css/cadastro.css">
  <style>
  input.err {
    border: 2px solid #ff0066 !important;
  }

  .aviso {
    display: none;
  }

  .green{background-color: green;}

  .red {background-color: red;}

  .divtempo2{color: #FFF; margin-bottom: 20px;}
  </style>

  <script>
  function marcarFalta(item) {


  }

  function copiarPlano() {
    var plano = document.getElementById("txtPlano").value;
    document.getElementById("txtConteudo").value = plano;
  }

  function carregar(id) {
    iddisciplina = id;
    document.body.innerHTML +=
      "<form id=\"dynForm\" action=\"notas.php\" method=\"post\"><input type=\"hidden\" name=\"idDisciplina\" value=\"" +
      iddisciplina + "\"'></form>";
    document.getElementById("dynForm").submit();
  }

  function verificaNota(el, id) {
    const nota = el.value.replace('.', ',')
    const digito = nota.split(',')[1]

    if (digito >= 0) {
      if (digito == 0 || digito == 5) {
        el.classList.remove('err')
        document.getElementById(`err-${id}`).style.display = 'none'
      } else {
        console.log(nota)
        el.classList.add('err')
        el.focus()
        document.getElementById(`err-${id}`).style.display = 'block'
      }
    } else {
      el.classList.remove('err')
      document.getElementById(`err-${id}`).style.display = 'none'
    }
  }

    function salvar() {

        $('form[action="gravarBoletim.php"]').each(function() {
         
            var dados = $(this).serializeArray();
            var data = {};

            for (var i=0; i<dados.length; i++) {
                data[dados[i].name] = dados[i].value;
            }

            $.ajax({
                type: 'POST',
                data: data,
                url: 'gravarBoletimAuto.php',
                success: function (data) {
                    var mensagem = "<div class='divtempo2 green'>Notas gravadas automaticamente com sucesso!</div>";
                    if (!data) mensagem = "<div class='divtempo2 red'>Ocorreu um erro na gravação das notas no modo automático. Tente através dos botões.</div>";

                    document.getElementById("divtempo").innerHTML = mensagem
                    setTimeout(function() { document.getElementById("divtempo").innerHTML = '' }, 5000);
                }
            });
        
        });

    }
    
    (function() {
        setInterval(salvar, 10000);
    })();
  </script>

</head>

<body>
  <?php
        if (isset($_SESSION["usuario"])) {
            if ($_SESSION["tipo"] == "professor") {
                include './conexao.php';
                include "funcaoNotas.php";
                include "topo.php";
                ?>
  <div class="voltar"><a href="index.php"><img src="imagens/voltar.png">Voltar</a></div>
  <div class="principal grid-100">
    <div id="titulo" class="grid-100 titulo">
      <h3>Lançamento de Notas</h3>
    </div>

    <br>
    <hr>
    <div class="grid-100" style="margin-top:30px;">
      <?php
                        //include './conexao.php';

                        $seguranca = new Seguranca();
                        $consulta = "";
                        if (!isset($_POST["idDisciplina"])) {
                            if (isset($_SESSION["id"])) {
                                $idProfessor = $_SESSION["id"];
                            } else {
                                if (isset($_POST["idProfessor"])) {
                                    $idProfessor = $_POST["idProfessor"];
                                } else {
                                    echo "<script>window.location='buscarProfessor.php'</script>";
                                }
                            }
                            //Localiza as disciplinas do professor
                            $sql = "SELECT idDisciplina,disciplina,disciplina.idTurma, turma.turma FROM disciplina,turma where disciplina.semestre = '". $_SESSION['semestre'] ."' AND idProfessor = '$idProfessor' AND disciplina.idTurma = turma.idTurma";
                            $resultados = mysql_query($sql);
                            $linhas = mysql_num_rows($resultados);
                            
                            if ($linhas > 0) {
                                echo "<table border='0' align='center' id='consulta' cellpadding='5' cellspacing='0'>
                                    <tr>
                                    <td>Disciplina</td>
                                    <td>Lançar Notas</td>
                                    </tr>";

                                for ($i = 0; $i < $linhas; $i++) {
                                    $idDisciplina = mysql_result($resultados, $i, "idDisciplina");
                                    $disciplina = mysql_result($resultados, $i, "disciplina");
                                    $idTurma = mysql_result($resultados, $i, "idTurma");
                                    $turma = mysql_result($resultados, $i, "turma");

                                    echo "<form method='post' action='notas.php'>
                                        <tr>
                                        <td>$disciplina - $turma</td>
                                        <input type='hidden' name='idDisciplina' value='$idDisciplina'>
                                        <input type='hidden' name='idTurma' value='$idTurma'>
                                        <td><input type='submit' value='Lançar Nota' class='botao'></td>
                                        </form>
                                        </tr>";
                                }

                                echo "</table>";
                            } else {
                                echo "Nenhuma registro encontrado.";
                            }
                        }
                        //Se o professor já escolheu a disciplina
                        if (isset($_POST["idDisciplina"])) {
                            $idDisciplina = $seguranca->antisql($_POST["idDisciplina"]);
                            $idTurma = "";
                            if(isset($_POST["idTurma"])){
                            $idTurma = $seguranca->antisql($_POST["idTurma"]);
                            }
                            
                            $sqlBoletim = "select idAluno from matricula where idTurma = '$idTurma' and idAluno not in (select idAluno from boletim where idDisciplina = '$idDisciplina')";
                            $resultBoletim = mysql_query($sqlBoletim);
                            $linhasBoletim = mysql_num_rows($resultBoletim);
                            if($linhasBoletim > 0){
                                for($n=0;$n < $linhasBoletim;$n++){
                                    $idAluno = mysql_result($resultBoletim, $n, "idAluno");
                                    $sql = "INSERT INTO boletim VALUES(null,'$idAluno','$idDisciplina','0','0','0','0','0','0')";
                                    mysql_query($sql);
                                }
                            }
                            
                            
                            $sqlAdaptado = "select idAluno from listadisciplina where idDisciplina = '$idDisciplina' and idAluno not in (select idAluno from boletim where idDisciplina = $idDisciplina)";
                            $resultAdaptado = mysql_query($sqlAdaptado);
                            $linhasAdaptado = mysql_num_rows($resultAdaptado);
                            if($linhasAdaptado > 0){
                                for($n=0;$n < $linhasAdaptado;$n++){
                                    $idAluno = mysql_result($resultAdaptado, $n, "idAluno");
                                    $sql = "INSERT INTO boletim VALUES(null,'$idAluno','$idDisciplina','0','0','0','0','0','0')";
                                    mysql_query($sql);
                                }
                            }
                            
                            
                            $sqlBoletim = "SELECT boletim.*,usuario.nome FROM boletim,usuario WHERE idDisciplina = '$idDisciplina' AND boletim.idAluno = usuario.idUsuario ORDER BY usuario.nome";
                            $resultBoletim = mysql_query($sqlBoletim);
                            $linhasBoletim = mysql_num_rows($resultBoletim);
                            if ($linhasBoletim > 0) {
                                echo "<button class='button divtempo2' onclick='salvar()'>Gravar Todos</button>";
                                echo "<div id='divtempo'></div>";

                                echo "<div style='width:34%;float:left;'>"
                                . "Nome"
                                . "</div>"
                                . "<div style='width:8%;float:left;'>P1</div>"
                                . "<div style='width:8%;float:left;'>A. Virtual I</div>"
                                . "<div style='width:8%;float:left;'>P2</div>"
                                . "<div style='width:8%;float:left;'>A. Virtual II</div>"
                                . "<div style='width:8%;float:left;'>Sub</div>"
                                . "<div style='width:8%;float:left;'>Exame</div>"
                                . "<div class='grid-10'>Operação</div><hr>";
                                

                                for ($i = 0; $i < $linhasBoletim; $i++) {
                                    $idBoletim = mysql_result($resultBoletim, $i, "idBoletim");
                                    $idAluno = mysql_result($resultBoletim, $i, "idAluno");
                                    $nome = mysql_result($resultBoletim, $i, "nome");
                                    $idDisciplina = mysql_result($resultBoletim, $i, "idDisciplina");
                                    $bimestre1 = mysql_result($resultBoletim, $i, "bimestre1");
                                    $bimestre2 = mysql_result($resultBoletim, $i, "bimestre2");
                                    $exame = mysql_result($resultBoletim, $i, "exame");
                                    $sub = mysql_result($resultBoletim, $i, "sub");
                                    $t1 = mysql_result($resultBoletim, $i, "t1");
                                    $t2 = mysql_result($resultBoletim, $i, "t2");


                                    $forumBm1 = getForum($idAluno, $idDisciplina, 1);
                                    $forumBm2 = getForum($idAluno, $idDisciplina, 2);

                                    $questionario1 = getQuestionario($idAluno, $idDisciplina, 1);
                                    $questionario2 = getQuestionario($idAluno, $idDisciplina, 2);

                                    $moduloBm1 = getModulo($idAluno, $idDisciplina, 1);
                                    $moduloBm2 = getModulo($idAluno, $idDisciplina, 2);

                                    $t1 = ($forumBm1 + $questionario1 + $moduloBm1);
                                    $t2 = ($forumBm2 + $questionario2 + $moduloBm2);

                                    $virtual1 = $t1 == 0 ? 0 : number_format($t1, 1, '.', '');
                                    $virtual2 = $t2 == 0 ? 0 : number_format($t2, 1, '.', '');

                                    $p1 = getProva($idAluno, $idDisciplina, 1);
                                    $p2 = getProva($idAluno, $idDisciplina, 2);
                                  
                                    $psub = getProva($idAluno, $idDisciplina, 3);
                                    $pexa = getProva($idAluno, $idDisciplina, 4);

                                    $b1readonly = ""; 
                                    $b2readonly = "";
                                    $subreadonly = ""; 
                                    $exareadonly = "";

                                    if ($p1 !== false) { $bimestre1 = $p1; $b1readonly = "readonly"; }
                                    if ($p2 !== false) { $bimestre2 = $p2; $b2readonly = "readonly"; }
                                    if ($psub !== false) { $sub = $psub; $subreadonly = "readonly"; }
                                    if ($pexa !== false) { $exame = $pexa; $exareadonly = "readonly"; }
                                    
                                    echo "<form method='post' action='gravarBoletim.php'>";
                                    echo "<input type='hidden' name='idBoletim' value='$idBoletim'>";
                                    echo "<input type='hidden' name='idDisciplina' value='$idDisciplina'>";
                                    echo "<div class='grid-100 chamada' id='falta$i' style='margin-bottom:10px;'>"
                                    . "<div style='width:34%;float:left;'>"
                                    . "<label>$nome</label>"
                                    . "</div>";                
                
                                    $props = 'min="0" max="10" onblur="verificaNota(this, '.$i.')"';
                                    
                                    echo 
                                      "<div style='width:8%;float:left;'><input type='text' name='txtBimestre1' $b1readonly value='$bimestre1' $props></div>"
                                    . "<div style='width:8%;float:left;' title='Nota calculada automaticamente'>$virtual1</div>"
                                    . "<div style='width:8%;float:left;'><input type='text' name='txtBimestre2' $b2readonly value='$bimestre2' $props></div>"
                                    . "<div style='width:8%;float:left;' title='Nota calculada automaticamente'>$virtual2</div>"
                                    . "<div style='width:8%;float:left;'><input type='text' name='txtSub' $subreadonly value='$sub' $props></div>"
                                    . "<div style='width:8%;float:left;'><input type='text' name='txtExame' $exareadonly value='$exame' $props></div>"
                                    
                                    . "<div class='grid-10'><input type='submit' value='Gravar Notas'></div>"
                                    . '<p class="aviso" id="err-'.$i.'" style="float: left;width: 100%;height: 20px;text-align: center;color: #FFF;">As notas devem ser inteiras ou fração de 0,5! Por favor reajuste.</p>'
                                    . "</div>";
                                    echo "</form>";
                                }

                                
                            } else {
                                $sqlAlunos = "SELECT u.idUsuario,u.nome FROM matricula m,usuario u WHERE m.idTurma = '$idTurma' and m.idAluno = u.idUsuario ORDER BY u.nome";
                                //echo $sqlAlunos;
                                $resultadosAlunos = mysql_query($sqlAlunos);
                                $linhasAlunos = mysql_num_rows($resultadosAlunos);
                                if ($linhasAlunos > 0) {
                                    for ($i = 0; $i < $linhasAlunos; $i++) {
                                        $idAluno = mysql_result($resultadosAlunos, $i, "idUsuario");
                                        $sql = "INSERT INTO boletim VALUES(null,'$idAluno','$idDisciplina','0','0','0','0','0','0')";
                                        mysql_query($sql);
                                    }
                                    echo "<script>"
                                    . "carregar($idDisciplina);"
                                            . "</script>";
                                }
                            }
                            /*
                              $sqlAlunos = "SELECT u.idUsuario,u.nome FROM matricula m,usuario u WHERE m.idTurma = '$idTurma' and m.idAluno = u.idUsuario ORDER BY u.nome";
                              //echo $sqlAlunos;
                              $resultadosAlunos = mysql_query($sqlAlunos);
                              $linhasAlunos = mysql_num_rows($resultadosAlunos);
                              if ($linhasAlunos > 0) { */
                        } else {
                            echo "<p>Nenhum aluno matriculado nesta turma.</p>";
                        }
                        ?>


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
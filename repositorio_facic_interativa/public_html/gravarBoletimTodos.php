<?php

session_start();

if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "professor") {
      
  include "conexao.php";
  $seguranca = new Seguranca();
  $dados = $_REQUEST["dados"];
  $retorno = "ok";

  foreach ($dados as $boletim) {
        $idBoletim    = $seguranca->antisql($boletim["idBoletim"]);
        $idDisciplina = $seguranca->antisql($boletim["idDisciplina"]);
        $bimestre1    = $seguranca->antisql($boletim["txtBimestre1"]);
        $bimestre2    = $seguranca->antisql($boletim["txtBimestre2"]);
        $exame        = $seguranca->antisql($boletim["txtExame"]);
        $sub          = $seguranca->antisql($boletim["txtSub"]);

        $sql = "UPDATE boletim SET bimestre1 = '$bimestre1', bimestre2 = '$bimestre2', 
            exame = '$exame', sub = '$sub' WHERE idBoletim = '$idBoletim'";

        if (!mysql_query($sql)){
            $retorno = "erro";   
        }
    }

  echo $retorno;
}


<?php
  date_default_timezone_set('America/Sao_Paulo');
  
  // ============================================
  // INICIALIZAÇÃO DO SENTRY - FACIC INTERATIVA - DESATIVADO TEMPORARIAMENTE
  // ============================================
  // DESATIVADO devido a erro 500 no login
  // Incluir configuração do Sentry ANTES de qualquer outra coisa
  /*
  if (file_exists(__DIR__ . '/sentry_config.php')) {
      require_once __DIR__ . '/sentry_config.php';
  }
  */
  
  if (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
    echo "<script>(function() { document.title = 'FACIC INTERATIVA' })()</script>";
  
  class Seguranca{ 
        function antisql($sql)
    {
      
      // remove palavras que contenham sintaxe sql
      //$sql = preg_replace(sql_regcase("/(from|sleep|select|insert|delete|where|drop table|show tables|'|#|\*|--|\\\\)/"),"",$sql);
      $sql = trim($sql);//limpa espaços vazio
      //$sql = strip_tags($sql);//tira tags html e php
      $sql = addslashes($sql);//Adiciona barras invertidas a uma string
      return $sql;
    }
    
  }
    
    //$conexao = mysql_connect("localhost","root","") or die("Não foi possivel conectar!");
    //mysql_select_db("ava",$conexao) or die("Banco inexistente!");
    

    $conexao = mysql_connect("interativa.vpshost0360.mysql.dbaas.com.br","interativa","Q!w2E#r4110452") or die("Não foi possivel conectar!");
    mysql_select_db("interativa",$conexao) or die("Banco inexistente!");
    
    
    // BLOQUEIA ALTERACOES DE COORDENADORES EM PERFIL DE PROFESSORES
    if (isset($_SESSION["coordenando"]) && $_SESSION["coordenando"] == true) {
      $__arquivo = basename($_SERVER['SCRIPT_FILENAME']);
      if (strpos($__arquivo, "operacao") !== false 
          || strpos($__arquivo, "alterar") !== false 
          || strpos($__arquivo, "gravar") !== false) {
            echo "<script>alert('Ops! Você não tem acesso a essa funcionalidade.'); "
              . "window.location='index.php';</script>";
            exit;
          }
    }
    
    ?>

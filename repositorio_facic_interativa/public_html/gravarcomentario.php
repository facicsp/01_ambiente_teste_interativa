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
  if($_SESSION["tipo"] == "aluno" || $_SESSION["tipo"] == "professor"){
      //conteudo do site
      include 'LoginRestrito/conexao.php';
      $seguranca = new Seguranca();
      $idUsuario = $_SESSION["id"];
      $comentario = $seguranca->antisql($_POST["txtComentario"]);
      $tipo = $_SESSION["tipo"];
      $idTopico = $seguranca->antisql($_POST["topico"]);







      $anexo = "NULL";
      $erro = false;
if (isset($_FILES["anexo"])) {
    $_UP['pasta'] = './mensagens/anexos-forum/';
    $_UP['tamanho'] = 1024 * 1024 * 10;
    $_UP['extensoes'] = array('pdf', 'png', 'jpg', 'doc', 'docx');

    // Array com os tipos de erros de upload do PHP
    $_UP['erros'][0] = 'Não houve erro';
    $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especificado';
    $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
    $_UP['erros'][6] = 'Faltando uma pasta temporária';
    $_UP['erros'][7] = 'Falha ao gravar arquivo no disco';
    $_UP['erros'][8] = 'Uma extensão PHP interrompeu o upload do arquivo';

    // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES['anexo']['error'] != 0) {
        $erro = "Não foi possível fazer o upload, erro: " . $_FILES['anexo']['error'];
        //$_UP['erros'][$_FILES['anexo']['error']];
    } else {
        // Faz a verificação da extensão do arquivo
        $extensao = strtolower(end(explode('.', $_FILES['anexo']['name'])));
        if (array_search($extensao, $_UP['extensoes']) === false) {
            $erro = "Por favor, envie arquivos com as seguintes extensões: " . join($_UP['extensoes'], ", ");
        }
        // Faz a verificação do tamanho do arquivo
        else if ($_UP['tamanho'] < $_FILES['anexo']['size']) {
            $erro = "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
        }

        // O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
        else {
            // Cria um nome baseado no UNIX TIMESTAMP atual
            $caminho = $_UP['pasta'] . md5($_FILES['anexo']['tmp_name']) . time() . "." . $extensao;

            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES['anexo']['tmp_name'], $caminho)) {
                $anexo = "'$caminho'";
            } else {
                // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                $erro = "Não foi possível enviar o arquivo, tente novamente";
            }
        }
    }
}

if ($erro != false) { $anexo = "NULL"; }








      
      $sql = "INSERT INTO comentario VALUES(null,'$comentario','$idUsuario','$tipo','$idTopico', DEFAULT, $anexo)";

    //   echo "<script>console.log(\"$sql\");</script>";

    mysqli_query($conexao, $sql);


    echo "<script>
alert('Gravação realizada com sucesso!');
window.location = 'forum.php?idTopico=$idTopico';
</script>
";

}else{
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
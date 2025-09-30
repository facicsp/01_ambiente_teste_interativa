<?php
header("Access-Control-Allow-Origin: http://ava24horas.com");
$code = $_REQUEST["code"];
if($code == "dhdhd7dyuy78iuh3uy78yueh28"){
    
    $curso = $_REQUEST["curso"];
    $turma = $_REQUEST["turma"];
    $professor = $_REQUEST["professor"];
    $disciplina = $_REQUEST["disciplina"];
    
    $retorno = "<span>Obs.: Quando nenhum dado semelhante for encontrado, quer dizer que serão utilizados os dados gerados por esse sistema.</span>";
    
    include 'LoginRestrito/conexao.php';
    //Localiza o curso
    $sql = "SELECT * FROM curso WHERE descricao LIKE '$curso'";
    $result = mysqli_query($conexao, $sql);
    $linhas = mysqli_num_rows($result);
    $comboCurso = "<label>Cursos Semelhantes Encontrados:</label><select id='curso'>";
    if($linhas > 0){
        
        for($i=0;$i<$linhas;$i++){
            $idCurso = mysql_result($result, $i, "idCurso");
            $curso = mysql_result($result, $i, "descricao");
            $comboCurso .= "<option value='$idCurso'>$curso</option>";
        }
        $comboCurso .= "<option value='0'>Nenhum semelhante - Cadastrar Novo.</option>";
    }else{
            $comboCurso .= "<option value='0'>Nenhum semelhante - Cadastrar Novo.</option>";
    }
    $comboCurso .= "</select>";
    
    //Localiza a turma
    $sql = "SELECT * FROM turma WHERE turma LIKE '$turma'";
    $result = mysqli_query($conexao, $sql);
    $linhas = mysqli_num_rows($result);
    $comboTurma = "<label>Turmas Semelhantes Encontradas:</label><select id='turma'>";
    if($linhas > 0){
        
        for($i=0;$i<$linhas;$i++){
            $idTurma = mysql_result($result, $i, "idTurma");
            $turma = mysql_result($result, $i, "turma");
            $comboTurma .= "<option value='$idTurma'>$turma</option>";
        }
        $comboTurma .= "<option value='0'>Nenhuma semelhante - Cadastrar Nova.</option>";
    }else{
            $comboTurma .= "<option value='0'>Nenhuma semelhante - Cadastrar Nova.</option>";
    }
    $comboTurma .= "</select>";
    
    //Localiza professor
    $sql = "SELECT * FROM professor WHERE nome LIKE '$professor'";
    $result = mysqli_query($conexao, $sql);
    $linhas = mysqli_num_rows($result);
    $comboProfessor = "<label>Professores Semelhantes Encontrados:</label><select id='professor'>";
    if($linhas > 0){
        
        for($i=0;$i<$linhas;$i++){
            $idProfessor = mysql_result($result, $i, "idProfessor");
            $professor = mysql_result($result, $i, "nome");
            $comboProfessor .= "<option value='$idProfessor'>$professor</option>";
        }
        $comboProfessor .= "<option value='0'>Nenhum semelhante - Cadastrar Novo.</option>";
    }else{
            $comboProfessor .= "<option value='0'>Nenhum semelhante - Cadastrar Novo.</option>";
    }
    $comboProfessor .= "</select>";
    
    $botao = "<input type='button' value='Confirmar Dados' onclick='confirmardados()'>";
    
    $retorno .= $comboCurso.$comboTurma.$comboProfessor.$botao;
    
    echo $retorno;
    
    
    
}else{
    echo "Acesso limitado. Entre em contato com o desenvolvedor.";
}


?>